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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tiket');
            $table->enum('jenis_tiket', ['reguler', 'event']);
            $table->string('sub_kategori')->nullable();
            $table->string('kategori_pengunjung')->nullable();
            $table->integer('harga');
            $table->integer('kuota');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
