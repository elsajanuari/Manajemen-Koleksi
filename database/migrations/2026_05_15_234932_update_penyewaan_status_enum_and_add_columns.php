<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update enum status penyewaan agar sesuai alur baru
        \DB::statement("ALTER TABLE penyewaan MODIFY COLUMN status ENUM(
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
            'dibatalkan',
            'cancelled',
            'rejected',
            'waiting_payment',
            'preparing_delivery',
            'delivered',
            'active',
            'completed'
        ) DEFAULT 'draft'");

        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dateTime('returned_at')->nullable()->after('rental_started_at');
            $table->dateTime('completed_at')->nullable()->after('returned_at');
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn(['returned_at', 'completed_at']);
        });
    }
};