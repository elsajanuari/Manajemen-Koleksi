<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->string('return_shipment_status')->nullable()->after('return_shipment_tracking');
            $table->json('return_shipment_timeline')->nullable()->after('return_shipment_status');
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn(['return_shipment_status', 'return_shipment_timeline']);
        });
    }
};
