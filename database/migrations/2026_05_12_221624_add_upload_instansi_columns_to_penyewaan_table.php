<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            // Cek dulu kolom mana yang belum ada, tambahkan semuanya
            if (!Schema::hasColumn('penyewaan', 'upload_surat_pengajuan')) {
                $table->string('upload_surat_pengajuan')->nullable()->after('upload_denah');
            }
            if (!Schema::hasColumn('penyewaan', 'upload_ktp_pic')) {
                $table->string('upload_ktp_pic')->nullable()->after('upload_surat_pengajuan');
            }
            if (!Schema::hasColumn('penyewaan', 'upload_npwp_instansi')) {
                $table->string('upload_npwp_instansi')->nullable()->after('upload_ktp_pic');
            }
            if (!Schema::hasColumn('penyewaan', 'upload_proposal')) {
                $table->string('upload_proposal')->nullable()->after('upload_npwp_instansi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn([
                'upload_surat_pengajuan',
                'upload_ktp_pic',
                'upload_npwp_instansi',
                'upload_proposal',
            ]);
        });
    }
};