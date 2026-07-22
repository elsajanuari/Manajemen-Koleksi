<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            // Cek dulu kolom yang sudah ada, tambah yang belum ada
            if (!Schema::hasColumn('penyewaan', 'kecamatan_id_domisili')) {
                $table->string('kecamatan_id_domisili')->nullable()->after('city_id_domisili');
            }
            if (!Schema::hasColumn('penyewaan', 'kecamatan_id_instansi')) {
                $table->string('kecamatan_id_instansi')->nullable()->after('city_id_instansi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumnIfExists('kecamatan_id_domisili');
            $table->dropColumnIfExists('kecamatan_id_instansi');
        });
    }
};