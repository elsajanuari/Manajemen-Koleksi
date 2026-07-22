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
        Schema::table('kondisi_koleksis', function (Blueprint $table) {
            $table->string('foto_sebelum')->nullable()->after('foto')->comment('Foto kondisi sebelum pemeriksaan');
            $table->string('foto_kondisi_saat_ini')->nullable()->after('foto_sebelum')->comment('Foto kondisi saat ini');
            $table->string('foto_kerusakan')->nullable()->after('foto_kondisi_saat_ini')->comment('Foto detail kerusakan');
            $table->string('rekomendasi_tindak_lanjut')->nullable()->after('foto_kerusakan')->comment('Rekomendasi tindak lanjut: tidak_perlu_tindakan, pemeliharaan (konservasi preventif, hanya kondisi baik), penanganan_kerusakan (konservasi kuratif), pemeriksaan_ulang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kondisi_koleksis', function (Blueprint $table) {
            $table->dropColumn(['foto_sebelum', 'foto_kondisi_saat_ini', 'foto_kerusakan', 'rekomendasi_tindak_lanjut']);
        });
    }
};
