<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;
use App\Models\SerahTerima;

class Penyewaan extends Model
{
    use HasFactory;

    protected $table = 'penyewaan';

    protected $fillable = [
        // Meta
        'user_id',
        'koleksi_id',        
        'status',
        'submission_status',
        'current_step',
        'submitted_at',

        // Step 1 — Jenis Penyewa
        'rental_type',

        // Step 2 — Info Pribadi (Perseorangan)
        'contact_name',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'kewarganegaraan',
        'negara_asal',
        'pekerjaan',
        'npwp',

        // Step 2 — Info Instansi
        'nama_instansi',
        'jenis_instansi',
        'bidang_usaha',
        'email_instansi',
        'telepon_kantor',
        'website_instansi',
        'alamat_instansi',
        'provinsi_instansi',
        'kota_instansi',
        'kode_pos_instansi',
        'rt_instansi',                 // ⬅️ BARU
        'rw_instansi',                 // ⬅️ BARU
        'kecamatan_instansi',          // ⬅️ BARU
        'kelurahan_desa_instansi',     // ⬅️ BARU
        'province_id_instansi',        // ⬅️ BARU
        'city_id_instansi',            // ⬅️ BARU
        'kecamatan_id_instansi',       // ⬅️ tambah
        'npwp_instansi',
        'nomor_nib',
        'nomor_siup',
        'nama_pic',
        'jabatan_pic',
        'nik_pic',
        'hp_pic',
        'email_pic',

        // Step 3 — Kontak & Alamat
        'contact_phone',
        'contact_email',
        'alamat_ktp',
        'alamat_domisili',
        'rt',
        'rw',
        'kelurahan_desa',
        'kecamatan',                   // ⬅️ BARU
        'provinsi',
        'kota_kabupaten',
        'kode_pos',
        'province_id_domisili',        // ⬅️ BARU
        'city_id_domisili',            // ⬅️ BARU
        'duration_days',
        'subtotal_amount',
        'kecamatan_id_domisili',       // ⬅️ tambah


        // Step 4 — Data Penyewaan
        'start_date',
        'end_date',
        'nama_tempat',
        'jenis_tempat',
        'indoor_outdoor',
        'alamat_lengkap',
        'kota_lokasi',
        'tujuan_penyewaan',
        'jumlah_pengunjung',
        'deskripsi_kegiatan',
        'cctv',
        'keamanan',
        'ber_ac',
        'risiko_cuaca',

        // Step 5 — Penagihan
        'invoice_name',
        'invoice_email',
        'invoice_address',
        'payment_method',
        'bank_name',
        'account_holder',
        'account_number',
        'agree_terms',           // ⬅️ BARU
        'agree_responsibility',  // ⬅️ BARU
        'agree_privacy',         // ⬅️ BARU
        'deposit_amount',
        'deposit_status',

        // Step 5 — Upload Dokumen (Perseorangan)
        'upload_ktp',
        'upload_selfie_ktp',
        'upload_npwp',
        'upload_foto_lokasi',
        'upload_denah',

        // Step 5 — Upload Dokumen (Instansi)
        'upload_surat_pengajuan',
        'upload_ktp_pic',
        'upload_npwp_instansi',
        'upload_proposal',

        // Verifikasi internal
        'verification_notes',
        'rejection_reason',
        'revision_notes',
        'agreement_document_path',
        'invoice_document_path',
        'signed_agreement_path',
        'signed_agreement_status',
        'signed_agreement_review_notes',
        'payment_status',
        'payment_reference',

        // ── PENGIRIMAN (baru) ──────────────────────────────────────────
        'shipping_zone_id',       // FK ke shipping_zones
        'shipping_cost',          // Ongkir snapshot (0 = gratis/pengelola)
        'shipping_method_type',   // 'courier' | 'manager'
        'courier_name',           // Nama kurir, null jika pengelola
        'courier_service',        // Layanan kurir (REG, OKE, dll)
        'courier_etd',            // Estimasi tiba
        'destination_city_id',    // RajaOngkir city ID untuk fetch tarif
        'city_name',              // Nama kota teks untuk Binderbyte
        'province_id_lokasi',   // ⬅️ BARU
        'total_bayar',            // Total = subtotal + deposit + ongkir
        'catatan_pengelola',      // Catatan opsional dari pengelola ke penyewa

        // Delivery fields
        'delivery_at',
        'received_at',
        'rental_started_at',

        // Legacy fields (untuk kompatibilitas backward)
        'full_address',
        'institution_name',
        'purpose',
        'notes',
    ];

    protected $casts = [
        'start_date'        => 'date',
        'end_date'          => 'date',
        'tanggal_lahir'     => 'date',
        'submitted_at'      => 'datetime',
        'delivery_at'       => 'datetime',
        'received_at'       => 'datetime',
        'rental_started_at' => 'datetime',
        'shipping_cost'     => 'decimal:0',
        'total_bayar'       => 'decimal:0',
        'agree_terms'          => 'boolean',
        'agree_responsibility' => 'boolean',
        'agree_privacy'        => 'boolean',
    ];

    public const STATUS_LABELS = [
        'draft'                         => 'Draft Pengajuan',
        'menunggu_verifikasi'           => 'Menunggu Verifikasi',
        'menunggu_dokumen_perjanjian'   => 'Upload Dokumen Perjanjian',
        'verifikasi_dokumen_perjanjian' => 'Dokumen Sedang Diverifikasi',
        'menunggu_pembayaran'           => 'Menunggu Pembayaran',
        'pengiriman'                    => 'Persiapan Pengiriman',
        'siap_diserahkan'               => 'Siap Diserahkan',
        'dalam_pengiriman'              => 'Dalam Pengiriman',
        'pengecekan_kondisi'            => 'Pengecekan Kondisi',
        'menunggu_review_kerusakan'     => 'Menunggu Review Kerusakan',
        'menunggu_data_rekening'        => 'Menunggu Data Rekening',
        'menunggu_penerimaan_koleksi'   => 'Menunggu Penerimaan Koleksi',
        'menunggu_refund_kerusakan'     => 'Menunggu Refund Kerusakan',
        'menunggu_dokumen_serah_terima' => 'Upload Dokumen Serah Terima',
        'verifikasi_serah_terima'       => 'Dokumen Sedang Diverifikasi',
        'aktif'                         => 'Masa Penyewaan Aktif',
        'pengembalian'                  => 'Proses Pengembalian',
        'selesai'                       => 'Penyewaan Selesai',
        'ditolak'                       => 'Pengajuan Ditolak',
        'dibatalkan'                    => 'Pengajuan Dibatalkan',
        'pemeriksaan_akhir'             => 'Pemeriksaan Akhir Koleksi',
        'menunggu_konfirmasi_refund'    => 'Menunggu Konfirmasi Refund Deposit',
        'menunggu_ttd_pengembalian'     => 'Menunggu TTD Dokumen Pengembalian',
        'menunggu_konfirmasi_selesai'   => 'Menunggu Konfirmasi Selesai',
        'menunggu_pembayaran_kerusakan' => 'Menunggu Pembayaran Kerusakan',
    ];

    /** Status yang masih berjalan — dipakai dashboard pengelola. */
    public const ACTIVE_STATUSES = [
        'menunggu_verifikasi',
        'menunggu_dokumen_perjanjian',
        'verifikasi_dokumen_perjanjian',
        'menunggu_pembayaran',
        'pengiriman',
        'siap_diserahkan',
        'dalam_pengiriman',
        'pengecekan_kondisi',
        'menunggu_review_kerusakan',
        'menunggu_data_rekening',
        'menunggu_penerimaan_koleksi',
        'menunggu_refund_kerusakan',
        'menunggu_konfirmasi_refund',
        'menunggu_dokumen_serah_terima',
        'verifikasi_serah_terima',
        'aktif',
        'pengembalian',
        'menunggu_ttd_pengembalian',
        'menunggu_pembayaran_kerusakan',
        'menunggu_konfirmasi_selesai',
    ];

    public const STATUS_BADGES = [
        'draft'                         => 'vp-badge-draft',
        'menunggu_verifikasi'           => 'vp-badge-menunggu',
        'menunggu_dokumen_perjanjian'   => 'vp-badge-menunggu',
        'verifikasi_dokumen_perjanjian' => 'vp-badge-menunggu',
        'menunggu_pembayaran'           => 'vp-badge-menunggu',
        'pengiriman'                    => 'vp-badge-disetujui',
        'siap_diserahkan'               => 'vp-badge-disetujui',
        'dalam_pengiriman'              => 'vp-badge-disetujui',
        'pengecekan_kondisi'            => 'vp-badge-menunggu',
        'menunggu_review_kerusakan'     => 'vp-badge-menunggu',
        'menunggu_data_rekening'        => 'vp-badge-menunggu',
        'menunggu_penerimaan_koleksi'   => 'vp-badge-menunggu',
        'menunggu_refund_kerusakan'     => 'vp-badge-menunggu',
        'menunggu_dokumen_serah_terima' => 'vp-badge-menunggu',
        'verifikasi_serah_terima'       => 'vp-badge-menunggu',
        'aktif'                         => 'vp-badge-disetujui',
        'pengembalian'                  => 'vp-badge-disetujui',
        'selesai'                       => 'vp-badge-disetujui',
        'ditolak'                       => 'vp-badge-ditolak',
        'dibatalkan'                    => 'vp-badge-ditolak',
        'pemeriksaan_akhir'             => 'vp-badge-menunggu',
        'menunggu_pembayaran_kerusakan' => 'vp-badge-menunggu',
    ];

    // ── Accessors ────────────────────────────────────────────────────────

    public function getStatusKeyAttribute(): string
    {
        return $this->status ?: 'draft';
    }

    public function getStatusLabelAttribute(): string
    {
        $statusKey = $this->status ?: 'draft';
        return self::STATUS_LABELS[$statusKey] ?? ucfirst(str_replace('_', ' ', $statusKey));
    }

    public function getStatusBadgeClassAttribute(): string
    {
        $statusKey = $this->status ?: 'draft';
        return self::STATUS_BADGES[$statusKey] ?? 'vp-badge-draft';
    }

    public function getDurationDaysAttribute(): int
    {
        if (! $this->start_date || ! $this->end_date) {
            return 0;
        }

        $start = $this->start_date;
        $end   = $this->end_date;

        // Pengaman: jika tersimpan terbalik (start > end), anggap saja tukar posisi
        if ($start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

        return max($start->diffInDays($end) + 1, 1);
    }

    /**
     * Label metode pengiriman — mirip Pembelian::getShippingMethodLabelAttribute()
     */
    public function getShippingMethodLabelAttribute(): string
    {
        return match ($this->shipping_method_type) {
            'courier' => 'Kurir (' . ($this->courier_name ?? '-') . ')',
            'manager' => 'Dikirim Pengelola',
            default   => '-',
        };
    }

    /**
     * Apakah metode pengiriman sudah ditentukan pengelola?
     */
    public function hasShippingDecided(): bool
    {
        return $this->shipping_method_type !== null;
    }

    /** Sinkronkan status penyewaan dengan handover_status (data lama / recovery). */
    public function syncLegacyShippingStatus(): void
    {
        $serahTerima = $this->serahTerima;
        if (! $serahTerima) {
            return;
        }

        $hs = $serahTerima->handover_status;

        if ($hs === 'preparing_delivery' && $this->status === 'pengiriman') {
            $this->update(['status' => 'siap_diserahkan']);
            return;
        }

        if ($hs === 'in_delivery' && in_array($this->status, ['pengiriman', 'siap_diserahkan'], true)) {
            $this->update(['status' => 'dalam_pengiriman']);
            return;
        }

        if ($hs === 'condition_checking' && $this->status === 'dalam_pengiriman') {
            $this->update(['status' => 'pengecekan_kondisi']);
        }
    }

    // ── Relasi ───────────────────────────────────────────────────────────

    public function koleksi()
    {
        return $this->belongsTo(Koleksi::class, 'koleksi_id');
    }

    // Alias backward compat — agar semua view/$penyewaan->painting masih jalan
    public function painting()
    {
        return $this->belongsTo(Koleksi::class, 'koleksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'penyewaan_id');
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class, 'penyewaan_id')->latestOfMany();
    }

    public function serahTerima()
    {
        return $this->hasOne(SerahTerima::class);
    }

    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }

    public function depositRefund()
    {
        return $this->hasOne(\App\Models\DepositRefund::class);
    }

    public function damageInvoice()
    {
        return $this->hasOne(\App\Models\DamageInvoice::class);
    }

    // ── URL Accessors ─────────────────────────────────────────────────────

    public function getAgreementUrlAttribute(): ?string
    {
        return $this->agreement_document_path ? asset('storage/' . $this->agreement_document_path) : null;
    }

    public function getInvoiceUrlAttribute(): ?string
    {
        return $this->invoice_document_path ? asset('storage/' . $this->invoice_document_path) : null;
    }

    public function getSignedAgreementUrlAttribute(): ?string
    {
        return $this->signed_agreement_path ? asset('storage/' . $this->signed_agreement_path) : null;
    }

    // ── Business Logic ────────────────────────────────────────────────────

    public function calculateDeposit(): int
    {
        if ($this->deposit_amount) {
            return (int) $this->deposit_amount;
        }

        if (! $this->koleksi || ! $this->start_date || ! $this->end_date) {
            return 0;
        }
        $durasi    = max($this->start_date->diffInDays($this->end_date) + 1, 1);
        $hargaSewa = $this->koleksi->daily_rate ?? 0;
        return (int) round(($hargaSewa * $durasi) * 0.5);
    }

    /** Refund pembatalan akibat kerusakan saat pengiriman: sewa + deposit (ongkir tidak dikembalikan). */
    public function calculateCancellationRefundAmount(): int
    {
        $subtotal = (int) ($this->subtotal_amount ?? 0);
        $deposit  = $this->calculateDeposit();

        return $subtotal + $deposit;
    }

    public function isArrivalDamageCancellation(): bool
    {
        $serahTerima = $this->serahTerima;

        if (! $serahTerima) {
            return false;
        }

        return $serahTerima->isArrivalDamageCancellation();
    }

    public function getDepositStatusLabelAttribute(): string
    {
        return match ($this->deposit_status) {
            'unpaid'                      => 'Belum Dibayar',
            'paid'                        => 'Dibayar',
            'returned'                    => 'Dikembalikan',
            'partially_returned'          => 'Dikembalikan Sebagian',
            'deducted'                    => 'Dipotong Kerusakan',
            'additional_payment_required' => 'Perlu Pembayaran Tambahan',
            default => ucfirst(str_replace('_', ' ', $this->deposit_status ?? '')),
        };
    }
}