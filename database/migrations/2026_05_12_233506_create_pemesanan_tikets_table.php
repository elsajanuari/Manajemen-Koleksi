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
        Schema::create('pemesanan_tikets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->date('tanggal_pemesanan');
            $table->integer('jumlah_tiket');
            $table->integer('total_harga');
            $table->enum('status', ['pending', 'menunggu_pembayaran', 'lunas', 'dibatalkan'])->default('pending');
            $table->enum('metode_pembayaran', ['transfer_bank', 'e_wallet', 'kartu_kredit'])->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan_tikets');
    }
};
