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
        Schema::create('detail_pengunjungs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_tiket_id')->constrained('pemesanan_tikets')->onDelete('cascade');
            $table->integer('urutan_pengunjung')->default(1); // Untuk tiket > 1
            
            // Field umum untuk semua tipe
            $table->string('email');
            $table->string('nomor_ponsel');
            $table->text('alamat');
            
            // Field untuk reguler, Sunday Painting Individu, Pameran
            $table->string('nama_lengkap')->nullable();
            
            // Field untuk Workshop
            $table->string('pendidikan')->nullable();
            
            // Field untuk Sunday Painting Kelompok
            $table->string('nama_kelompok')->nullable();
            $table->longText('daftar_anggota')->nullable(); // JSON atau list anggota
            $table->string('nama_penanggung_jawab')->nullable();
            $table->text('alamat_penanggung_jawab')->nullable();
            $table->string('nomor_ponsel_penanggung_jawab')->nullable();
            $table->string('email_penanggung_jawab')->nullable();
            
            // Tipe data (untuk fleksibilitas)
            $table->enum('tipe_pengunjung', ['individu', 'kelompok'])->default('individu');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengunjungs');
    }
};
