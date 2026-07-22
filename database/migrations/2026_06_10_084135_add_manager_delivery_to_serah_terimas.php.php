<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            // Sub-status pengiriman oleh pengelola (mirip Pembelian)
            $table->string('manager_delivery_status')->nullable()->after('delivery_tracking_number');
            // Timeline log bertahap: JSON array of {status, label, catatan, timestamp, by}
            $table->json('manager_delivery_timeline')->nullable()->after('manager_delivery_status');
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn(['manager_delivery_status', 'manager_delivery_timeline']);
        });
    }
};