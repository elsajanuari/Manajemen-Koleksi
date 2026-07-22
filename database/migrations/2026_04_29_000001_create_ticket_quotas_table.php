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
        Schema::create('ticket_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->date('tanggal'); // Tanggal berlaku untuk kuota ini
            $table->integer('kuota_max'); // Jumlah tiket max yang tersedia
            $table->integer('kuota_terjual')->default(0); // Jumlah tiket yang terjual
            $table->timestamps();

            // Index untuk query performance
            $table->index(['ticket_id', 'tanggal']);
            $table->unique(['ticket_id', 'tanggal']); // Satu kuota per tiket per hari
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_quotas');
    }
};
