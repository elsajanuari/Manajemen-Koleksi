<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom koleksi_id
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->unsignedBigInteger('koleksi_id')->nullable()->after('painting_id');
        });

        // 2. Copy data: ambil koleksi_id dari paintings berdasarkan painting_id
        DB::statement('
            UPDATE penyewaan p
            LEFT JOIN paintings pt ON pt.id = p.painting_id
            SET p.koleksi_id = pt.koleksi_id
        ');

        // 3. Hapus kolom painting_id
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropForeign(['painting_id']); // ← tambah ini
            $table->dropColumn('painting_id');
        });

        // 4. Tambah foreign key
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->foreign('koleksi_id')->references('id')->on('koleksis')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropForeign(['koleksi_id']);
            $table->dropColumn('koleksi_id');
            $table->unsignedBigInteger('painting_id')->nullable()->after('user_id');
            $table->foreign('painting_id')->references('id')->on('paintings')->nullOnDelete();
        });
    }
};