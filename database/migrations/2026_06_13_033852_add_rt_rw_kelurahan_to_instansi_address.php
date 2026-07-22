<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            // Instansi address
            $table->string('rt_instansi', 10)->nullable()->after('kota_instansi');
            $table->string('rw_instansi', 10)->nullable()->after('rt_instansi');
            $table->string('kecamatan_instansi')->nullable()->after('rw_instansi');
            $table->string('kelurahan_desa_instansi')->nullable()->after('kecamatan_instansi');

            // Domisili address — tambah kecamatan
            $table->string('kecamatan')->nullable()->after('kelurahan_desa');

            // Emsifa IDs
            $table->string('province_id_domisili', 10)->nullable()->after('kode_pos');
            $table->string('city_id_domisili', 10)->nullable()->after('province_id_domisili');
            $table->string('district_id_domisili', 10)->nullable()->after('city_id_domisili');

            $table->string('province_id_instansi', 10)->nullable()->after('kode_pos_instansi');
            $table->string('city_id_instansi', 10)->nullable()->after('province_id_instansi');
            $table->string('district_id_instansi', 10)->nullable()->after('city_id_instansi');
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn([
                'rt_instansi', 'rw_instansi',
                'kecamatan_instansi', 'kelurahan_desa_instansi',
                'kecamatan',
                'province_id_domisili', 'city_id_domisili', 'district_id_domisili',
                'province_id_instansi', 'city_id_instansi', 'district_id_instansi',
            ]);
        });
    }
};