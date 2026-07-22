<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paintings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('artist');
            $table->text('description');
            $table->integer('daily_rate')->default(150000);
            $table->boolean('available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paintings');
    }
};
