<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            if (!Schema::hasColumn('penyewaan', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('kelurahan_desa');
            }
            if (!Schema::hasColumn('penyewaan', 'kecamatan_instansi')) {
                $table->string('kecamatan_instansi')->nullable()->after('kelurahan_desa_instansi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn(['kecamatan', 'kecamatan_instansi']);
        });
    }
};