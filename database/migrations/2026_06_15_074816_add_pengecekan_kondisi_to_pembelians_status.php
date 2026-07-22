<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pembelians MODIFY COLUMN status ENUM(
            'menunggu_verifikasi',
            'disetujui',
            'ditolak',
            'menunggu_pembayaran',
            'pembayaran_berhasil',
            'siap_diserahkan',
            'dalam_pengiriman',
            'menunggu_dokumen_serah_terima',
            'menunggu_validasi_serah_terima',
            'diterima_pembeli',
            'pengecekan_kondisi',
            'menunggu_review_kerusakan',
            'selesai',
            'dibatalkan'
        ) NOT NULL DEFAULT 'menunggu_verifikasi'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pembelians MODIFY COLUMN status ENUM(
            'menunggu_verifikasi',
            'disetujui',
            'ditolak',
            'menunggu_pembayaran',
            'pembayaran_berhasil',
            'siap_diserahkan',
            'dalam_pengiriman',
            'menunggu_dokumen_serah_terima',
            'menunggu_validasi_serah_terima',
            'diterima_pembeli',
            'pengecekan_kondisi',
            'selesai',
            'dibatalkan'
        ) NOT NULL DEFAULT 'menunggu_verifikasi'");
    }
};