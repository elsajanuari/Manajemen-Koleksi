<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perawatan_koleksis', function (Blueprint $table) {
            $table->foreignId('penanggung_jawab_user_id')
                ->nullable()
                ->after('penanggung_jawab')
                ->constrained('users')
                ->nullOnDelete();

            $table->unsignedSmallInteger('estimasi_durasi_menit')
                ->nullable()
                ->after('frekuensi')
                ->comment('Estimasi durasi pelaksanaan dalam menit');
        });
    }

    public function down(): void
    {
        Schema::table('perawatan_koleksis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('penanggung_jawab_user_id');
            $table->dropColumn('estimasi_durasi_menit');
        });
    }
};
