<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conservation_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conservation_action_id')->constrained('conservation_actions')->cascadeOnDelete();
            $table->string('jenis_tindakan');
            $table->text('deskripsi_tindakan');
            $table->text('bahan_material')->nullable();
            $table->date('target_penyelesaian')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique('conservation_action_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conservation_plans');
    }
};