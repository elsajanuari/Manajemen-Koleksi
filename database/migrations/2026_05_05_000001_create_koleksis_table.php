<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koleksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori');
            $table->string('nomor_inventaris')->nullable();
            $table->string('seniman')->nullable();
            $table->string('tahun')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('status_sewa')->default('tidak');
            $table->string('lokasi')->default('disimpan');
            $table->string('kondisi')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('koleksis');
    }
};
