<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->text('company_kecamatan')->nullable()->after('company_kelurahan_desa');

            $table->text('alamat_domisili')->nullable()->after('npwp');
            $table->text('dom_provinsi')->nullable()->after('alamat_domisili');
            $table->text('dom_kota_kabupaten')->nullable()->after('dom_provinsi');
            $table->text('dom_kecamatan')->nullable()->after('dom_kota_kabupaten');
            $table->text('dom_kelurahan_desa')->nullable()->after('dom_kecamatan');
            $table->string('dom_rt', 10)->nullable()->after('dom_kelurahan_desa');
            $table->string('dom_rw', 10)->nullable()->after('dom_rt');
            $table->string('dom_kode_pos', 10)->nullable()->after('dom_rw');

            $table->text('pic_alamat_domisili')->nullable()->after('pic_email');
            $table->text('pic_provinsi')->nullable()->after('pic_alamat_domisili');
            $table->text('pic_kota_kabupaten')->nullable()->after('pic_provinsi');
            $table->text('pic_kecamatan')->nullable()->after('pic_kota_kabupaten');
            $table->text('pic_kelurahan_desa')->nullable()->after('pic_kecamatan');
            $table->string('pic_rt', 10)->nullable()->after('pic_kelurahan_desa');
            $table->string('pic_rw', 10)->nullable()->after('pic_rt');
            $table->string('pic_kode_pos', 10)->nullable()->after('pic_rw');
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn([
                'company_kecamatan',
                'alamat_domisili',
                'dom_provinsi',
                'dom_kota_kabupaten',
                'dom_kecamatan',
                'dom_kelurahan_desa',
                'dom_rt',
                'dom_rw',
                'dom_kode_pos',
                'pic_alamat_domisili',
                'pic_provinsi',
                'pic_kota_kabupaten',
                'pic_kecamatan',
                'pic_kelurahan_desa',
                'pic_rt',
                'pic_rw',
                'pic_kode_pos',
            ]);
        });
    }
};
