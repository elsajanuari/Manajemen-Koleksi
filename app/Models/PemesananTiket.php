<?php
// app/Models/PemesananTiket.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PemesananTiket extends Model
{
    use HasFactory;

    protected $table = 'pemesanan_tikets';

    protected $fillable = [
        'user_id',
        'ticket_id',
        'tanggal_pemesanan',
        'jumlah_tiket',
        'total_harga',
        'status',
        'metode_pembayaran',
        'midtrans_order_id',
        'tanggal_bayar',
        'bukti_pembayaran',
        'catatan',
        'dibatalkan_pada',
        'refund_requested_at',
        'refund_completed_at',
        'nama_bank_refund',
        'atas_nama_refund',
        'no_rekening_refund',
        'bukti_pengembalian',
        'tiket_verifikasi_token',
        'tiket_terpakai_at',
        'tiket_diverifikasi_oleh',
        'reschedule_pada',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_refund_key',
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'date',
        'tanggal_bayar' => 'datetime',
        'dibatalkan_pada' => 'datetime',
        'refund_requested_at' => 'datetime',
        'refund_completed_at' => 'datetime',
        'tiket_terpakai_at' => 'datetime',
        'reschedule_pada' => 'datetime',
        'total_harga' => 'integer',
        'jumlah_tiket' => 'integer',
    ];

    protected $appends = ['status_label'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function detailPengunjungs()
    {
        return $this->hasMany(DetailPengunjung::class);
    }

    // Status checks
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isWaitingPayment()
    {
        return $this->status === 'menunggu_pembayaran';
    }

    public function isPaid()
    {
        return $this->status === 'lunas';
    }

    public function isCancelled()
    {
        return $this->status === 'dibatalkan';
    }

    public function isRefundProcess()
    {
        return $this->status === 'proses_pembatalan';
    }

    public function isRefundCompleted()
    {
        return $this->status === 'pengembalian_berhasil';
    }

    /**
     * Cek apakah tiket sudah terpakai/discan
     */
    public function isTiketTerpakai()
    {
        if (!is_null($this->tiket_terpakai_at)) {
            return true;
        }
        return $this->detailPengunjungs()->whereNotNull('tiket_terpakai_at')->exists();
    }

    /**
     * Cek apakah status ini dianggap sebagai transaksi yang valid (tidak refund)
     * untuk perhitungan pendapatan
     */
    public function isValidForRevenue()
    {
        return $this->status === 'lunas';
    }

    /**
     * Cek apakah status ini pending refund (masih menunggu konfirmasi pengelola)
     */
    public function isRefundPending()
    {
        return $this->status === 'proses_pembatalan';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'lunas' => 'Lunas',
            'dibatalkan' => 'Dibatalkan',
            'proses_pembatalan' => 'Proses Refund',
            'pengembalian_berhasil' => 'Refund Berhasil',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getRepresentativeName()
    {
        $firstDetail = $this->detailPengunjungs()->first();
        
        if ($firstDetail) {
            if ($firstDetail->tipe_pengunjung === 'kelompok' && $firstDetail->nama_kelompok) {
                return $firstDetail->nama_kelompok . ' (Kelompok)';
            }
            
            if ($firstDetail->nama_lengkap) {
                return $firstDetail->nama_lengkap;
            }
            
            if ($firstDetail->nama_penanggung_jawab) {
                return $firstDetail->nama_penanggung_jawab . ' (PJ)';
            }
        }
        
        return $this->user->name ?? 'Pengguna';
    }

    public function getRepresentativeEmail()
    {
        $firstDetail = $this->detailPengunjungs()->first();
        
        if ($firstDetail) {
            if ($firstDetail->email) {
                return $firstDetail->email;
            }
            if ($firstDetail->email_penanggung_jawab) {
                return $firstDetail->email_penanggung_jawab;
            }
        }
        
        return $this->user->email ?? '-';
    }

    public function getRepresentativePhone()
    {
        $firstDetail = $this->detailPengunjungs()->first();
        
        if ($firstDetail) {
            if ($firstDetail->nomor_ponsel) {
                return $firstDetail->nomor_ponsel;
            }
            if ($firstDetail->nomor_ponsel_penanggung_jawab) {
                return $firstDetail->nomor_ponsel_penanggung_jawab;
            }
        }
        
        return '-';
    }

    public function dapatReschedule(): bool
    {
        if ($this->status !== 'lunas' && $this->status !== 'menunggu_pembayaran') {
            return false;
        }

        if (!$this->ticket || !$this->ticket->boleh_reschedule) {
            return false;
        }

        if ($this->isTiketTerpakai()) {
            return false;
        }

        return \App\Services\PemesananTiketModifikasiService::masihDalamBatasWaktu($this);
    }

    public function dapatCancel(): bool
    {
        if ($this->status === 'pending') {
            return true;
        }

        if ($this->status !== 'lunas' && $this->status !== 'menunggu_pembayaran') {
            return false;
        }

        if (!$this->ticket || !$this->ticket->boleh_cancel) {
            return false;
        }

        if ($this->isTiketTerpakai()) {
            return false;
        }

        return \App\Services\PemesananTiketModifikasiService::masihDalamBatasWaktu($this);
    }

    public function isDetailPengunjungComplete(): bool
    {
        $detailCount = $this->detailPengunjungs()->count();

        if ($detailCount === 0) {
            return false;
        }

        $ticket = $this->ticket;
        $isKelompok = $ticket &&
                    strtolower((string) $ticket->jenis_tiket) === 'event' &&
                    strtolower((string) $ticket->sub_jenis) === 'sunday painting' &&
                    (string) $ticket->kategori_pengunjung === 'Kelompok';

        if ($isKelompok) {
            return $this->detailPengunjungs()
                        ->where('tipe_pengunjung', 'kelompok')
                        ->exists();
        }

        return $detailCount >= $this->jumlah_tiket;
    }

    public function batasWaktuModifikasi(): \Carbon\Carbon
    {
        return \App\Services\PemesananTiketModifikasiService::batasWaktuModifikasi($this);
    }

    public function isCancelRequested(): bool
    {
        return $this->status === 'proses_pembatalan';
    }

    public function isRefundDone(): bool
    {
        return $this->status === 'pengembalian_berhasil';
    }

    public function expireJikaKedaluwarsa(): self
    {
        if (
            $this->status === 'menunggu_pembayaran' &&
            $this->tanggal_pemesanan->lt(now('Asia/Jakarta')->startOfDay())
        ) {
            $this->update([
                'status' => 'dibatalkan',
                'dibatalkan_pada' => now(),
                'catatan' => trim(($this->catatan ? $this->catatan . ' | ' : '')
                    . 'Dibatalkan otomatis: tidak ada pembayaran hingga tanggal kunjungan.'),
            ]);
        }

        return $this;
    }
}