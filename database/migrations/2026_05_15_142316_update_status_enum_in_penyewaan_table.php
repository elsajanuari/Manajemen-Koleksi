<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE penyewaan MODIFY COLUMN status ENUM(
            'draft',
            'menunggu_verifikasi',
            'menunggu_dokumen_perjanjian',
            'verifikasi_dokumen_perjanjian',
            'menunggu_pembayaran',
            'pengiriman',
            'menunggu_dokumen_serah_terima',
            'verifikasi_serah_terima',
            'aktif',
            'pengembalian',
            'selesai',
            'ditolak',
            'dibatalkan'
        ) DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            //
        });
    }
};
