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
        Schema::table('ticket_schedule_exceptions', function (Blueprint $table) {
            // Tambah kolom jika belum ada
            if (!Schema::hasColumn('ticket_schedule_exceptions', 'alasan')) {
                $table->string('alasan')->nullable()->after('is_tersedia');
            }
            if (!Schema::hasColumn('ticket_schedule_exceptions', 'jam_buka')) {
                $table->time('jam_buka')->nullable()->after('alasan');
            }
            if (!Schema::hasColumn('ticket_schedule_exceptions', 'jam_tutup')) {
                $table->time('jam_tutup')->nullable()->after('jam_buka');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_schedule_exceptions', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_schedule_exceptions', 'alasan')) {
                $table->dropColumn('alasan');
            }
            if (Schema::hasColumn('ticket_schedule_exceptions', 'jam_buka')) {
                $table->dropColumn('jam_buka');
            }
            if (Schema::hasColumn('ticket_schedule_exceptions', 'jam_tutup')) {
                $table->dropColumn('jam_tutup');
            }
        });
    }
};
