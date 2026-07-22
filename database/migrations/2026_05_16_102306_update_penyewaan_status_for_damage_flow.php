<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah status 'pemeriksaan_akhir' dan 'menunggu_pembayaran_kerusakan' ke kolom status penyewaan
        \DB::statement("ALTER TABLE penyewaan MODIFY COLUMN status VARCHAR(100) NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Tidak perlu rollback khusus karena varchar menampung semua nilai
    }
};