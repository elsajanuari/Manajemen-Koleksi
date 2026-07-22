<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyewaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('painting_id')->constrained('paintings')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('contact_phone');
            $table->text('notes')->nullable();
            $table->enum('status', ['menunggu verifikasi', 'disetujui', 'dibatalkan', 'ditolak'])
                  ->default('menunggu verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyewaan');
    }
};
