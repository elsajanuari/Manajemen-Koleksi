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
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dateTime('return_shipped_at')->nullable()->after('return_shipment_submitted_at');
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn('return_shipped_at');
        });
    }
};
