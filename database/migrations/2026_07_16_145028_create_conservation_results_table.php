<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conservation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conservation_action_id')->constrained('conservation_actions')->cascadeOnDelete();
            $table->string('kondisi_setelah');
            $table->string('foto_setelah')->nullable();
            $table->string('evaluasi'); 
            $table->text('rekomendasi_penyimpanan')->nullable();
            $table->text('rekomendasi_penanganan_khusus')->nullable();
            $table->text('catatan_akhir')->nullable();
            $table->timestamps();

            $table->unique('conservation_action_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conservation_results');
    }
};