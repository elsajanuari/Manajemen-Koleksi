<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengunjung extends Model
{
    protected $table = 'detail_pengunjungs';

    protected $fillable = [
        'pemesanan_tiket_id',
        'urutan_pengunjung',
        'nama_lengkap',
        'pendidikan',
        'email',
        'nomor_ponsel',
        'alamat',
        'bukti_pelajar_path',
        'nama_kelompok',
        'daftar_anggota',
        'nama_penanggung_jawab',
        'alamat_penanggung_jawab',
        'nomor_ponsel_penanggung_jawab',
        'email_penanggung_jawab',
        'tipe_pengunjung',
        'tiket_verifikasi_token',
        'tiket_terpakai_at',
    ];

    protected $casts = [
        'daftar_anggota' => 'array',
        'tiket_terpakai_at' => 'datetime',
    ];

    public function pemesananTiket(): BelongsTo
    {
        return $this->belongsTo(PemesananTiket::class);
    }

    public function getDisplayName(): string
    {
        if ($this->tipe_pengunjung === 'kelompok') {
            return $this->nama_kelompok ?: $this->nama_penanggung_jawab;
        }

        return $this->nama_lengkap ?? 'Pengunjung';
    }

    public function isIndividu(): bool
    {
        return $this->tipe_pengunjung === 'individu';
    }

    public function isKelompok(): bool
    {
        return $this->tipe_pengunjung === 'kelompok';
    }
}