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
            // Make customer info fields nullable since they're filled in step 2
            $table->string('contact_name')->nullable()->change();
            $table->string('contact_email')->nullable()->change();
            $table->text('full_address')->nullable()->change();
            $table->text('purpose')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->string('contact_name')->nullable(false)->change();
            $table->string('contact_email')->nullable(false)->change();
            $table->text('full_address')->nullable(false)->change();
            $table->text('purpose')->nullable(false)->change();
        });
    }
};
