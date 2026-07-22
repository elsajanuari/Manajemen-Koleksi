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
        Schema::table('tickets', function (Blueprint $table) {
            // Tambah kolom sub_pameran jika belum ada
            if (!Schema::hasColumn('tickets', 'sub_pameran')) {
                $table->enum('sub_pameran', ['pameran_rutin', 'pameran_berkala', 'pameran_museum'])
                      ->nullable()
                      ->after('sub_kategori')
                      ->comment('Sub kategori untuk Pameran: Rutin, Berkala, Museum');
            }

            // Rename sub_kategori menjadi sub_jenis jika belum ada sub_jenis
            if (Schema::hasColumn('tickets', 'sub_kategori') && !Schema::hasColumn('tickets', 'sub_jenis')) {
                $table->renameColumn('sub_kategori', 'sub_jenis');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'sub_pameran')) {
                $table->dropColumn('sub_pameran');
            }
        });
    }
};
