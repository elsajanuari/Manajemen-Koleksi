<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('penyewaan', 'jenis_instansi')) {
            DB::statement("ALTER TABLE penyewaan MODIFY jenis_instansi VARCHAR(100) NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('penyewaan', 'jenis_instansi')) {
            DB::statement("ALTER TABLE penyewaan MODIFY jenis_instansi ENUM('Perusahaan Swasta','BUMN / BUMD','Instansi Pemerintah','Yayasan / NGO','Lembaga Pendidikan','Komunitas / Organisasi','Lainnya') NULL");
        }
    }
};
