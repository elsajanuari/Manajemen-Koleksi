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
        Schema::table('pembelians', function (Blueprint $table) {
            $table->string('arrival_damage_manager_decision')->nullable();
            // 'setujui_pembatalan', 'tolak_pembatalan', 'setujui_kompensasi'
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn('arrival_damage_manager_decision');
        });
    }
};
