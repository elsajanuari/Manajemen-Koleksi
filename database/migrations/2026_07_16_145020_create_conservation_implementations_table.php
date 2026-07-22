<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conservation_implementations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conservation_action_id')->constrained('conservation_actions')->cascadeOnDelete();
            $table->date('tanggal_pelaksanaan');
            $table->string('petugas');
            $table->string('durasi')->nullable();
            $table->text('catatan_pelaksanaan');
            $table->string('foto_proses')->nullable();
            $table->text('catatan_perubahan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conservation_implementations');
    }
};