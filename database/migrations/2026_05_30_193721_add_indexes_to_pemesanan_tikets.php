<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Index untuk optimasi query laporan
        Schema::table('pemesanan_tikets', function (Blueprint $table) {
            $table->index(['status', 'tanggal_bayar']);
            $table->index(['tanggal_bayar', 'status']);
            $table->index('metode_pembayaran');
        });

        Schema::table('detail_pengunjungs', function (Blueprint $table) {
            $table->index('email');
            $table->index('nomor_ponsel');
            $table->index('tiket_terpakai_at');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan_tikets', function (Blueprint $table) {
            $table->dropIndex(['status', 'tanggal_bayar']);
            $table->dropIndex(['tanggal_bayar', 'status']);
            $table->dropIndex(['metode_pembayaran']);
        });

        Schema::table('detail_pengunjungs', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['nomor_ponsel']);
            $table->dropIndex(['tiket_terpakai_at']);
        });
    }
};