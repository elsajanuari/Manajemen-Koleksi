<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kondisi_koleksis', function (Blueprint $table) {
            $table->decimal('suhu', 5, 2)->nullable()->comment('Suhu lingkungan koleksi dalam derajat Celcius');
            $table->unsignedTinyInteger('kelembapan')->nullable()->comment('Kelembapan relatif lingkungan (%)');
            $table->string('pencahayaan')->nullable()->comment('Tingkat pencahayaan lingkungan');
            $table->string('jenis_kerusakan')->nullable()->comment('Jenis kerusakan atau temuan fisik');
            $table->text('kondisi_lingkungan')->nullable()->comment('Deskripsi kondisi lingkungan saat pemeriksaan');
        });
    }

    public function down(): void
    {
        Schema::table('kondisi_koleksis', function (Blueprint $table) {
            $table->dropColumn(['suhu', 'kelembapan', 'pencahayaan', 'jenis_kerusakan', 'kondisi_lingkungan']);
        });
    }
};
