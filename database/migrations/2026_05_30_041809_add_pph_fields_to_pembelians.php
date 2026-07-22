<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            // Tarif PPh yang dikenakan (1.5 atau 3.0)
            $table->decimal('pph_rate', 4, 2)->default(0)->after('ppn');

            // Nominal PPh dalam rupiah
            $table->bigInteger('pph')->default(0)->after('pph_rate');

            // Keterangan status PPh
            // 'npwp'         = punya NPWP valid   → tarif 1.5%
            // 'no_npwp'      = tidak punya NPWP   → tarif 3%
            // 'not_final'    = belum diverifikasi (estimasi)
            $table->enum('pph_status', ['not_final', 'npwp', 'no_npwp'])
                  ->default('not_final')
                  ->after('pph');
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn(['pph_rate', 'pph', 'pph_status']);
        });
    }
};