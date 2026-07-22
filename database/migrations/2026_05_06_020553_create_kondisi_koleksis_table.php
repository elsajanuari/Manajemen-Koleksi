<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kondisi_koleksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('koleksi_id')->constrained('koleksis')->cascadeOnDelete();
            $table->date('tanggal_periksa');
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat']);
            $table->string('pemeriksa');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kondisi_koleksis');
    }
};