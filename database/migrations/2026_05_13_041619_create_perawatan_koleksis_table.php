<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perawatan_koleksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('koleksi_id')->constrained('koleksis')->cascadeOnDelete();
            $table->string('jenis_perawatan'); 
            $table->date('jadwal_tanggal');
            $table->string('frekuensi')->default('sekali'); 
            $table->string('penanggung_jawab');
            $table->text('catatan')->nullable();
            $table->string('status')->default('terjadwal'); 
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan_penyelesaian')->nullable();
            $table->string('alasan_pembatalan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perawatan_koleksis');
    }
};