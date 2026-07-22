<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            // Ubah enum status untuk tambah nilai baru
            $table->enum('status', [
                'menunggu_verifikasi',
                'disetujui',
                'ditolak',
                'menunggu_pembayaran',
                'pembayaran_berhasil',
                'siap_diserahkan',
                'dalam_pengiriman',
                'diterima_pembeli',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_verifikasi')->change();

            // Info pengiriman oleh pengelola
            $table->string('delivery_method')->nullable()->after('paid_at');
            $table->string('delivery_officer')->nullable()->after('delivery_method');
            $table->string('delivery_tracking_number')->nullable()->after('delivery_officer');
            $table->text('delivery_location')->nullable()->after('delivery_tracking_number');
            $table->string('recipient_name')->nullable()->after('delivery_location');
            $table->timestamp('delivery_scheduled_at')->nullable()->after('recipient_name');
            $table->text('delivery_notes')->nullable()->after('delivery_scheduled_at');
            $table->timestamp('shipped_at')->nullable()->after('delivery_notes');

            // Konfirmasi pembeli
            $table->timestamp('received_at')->nullable()->after('shipped_at');

            // Selesai
            $table->timestamp('completed_at')->nullable()->after('received_at');
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_method',
                'delivery_officer',
                'delivery_tracking_number',
                'delivery_location',
                'recipient_name',
                'delivery_scheduled_at',
                'delivery_notes',
                'shipped_at',
                'received_at',
                'completed_at',
            ]);

            $table->enum('status', [
                'menunggu_verifikasi',
                'disetujui',
                'ditolak',
                'menunggu_pembayaran',
                'pembayaran_berhasil',
                'dibatalkan',
            ])->default('menunggu_verifikasi')->change();
        });
    }
};