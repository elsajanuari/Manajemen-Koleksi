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

            $table->string('condition_front_photo')->nullable();

            $table->string('condition_back_photo')->nullable();

            $table->string('damage_video_path')->nullable();

            $table->string('arrival_damage_buyer_decision')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            //
        });
    }
};
