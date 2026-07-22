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
        Schema::table('penyewaan', function (Blueprint $table) {
            // Make date fields nullable since they're filled in step 2 or later
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
            $table->string('contact_phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->date('start_date')->nullable(false)->change();
            $table->date('end_date')->nullable(false)->change();
            $table->string('contact_phone')->nullable(false)->change();
        });
    }
};
