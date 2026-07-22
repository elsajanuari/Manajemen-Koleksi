<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->string('return_shipment_method')->nullable()->after('refund_confirmed_at');
            $table->string('return_shipment_officer')->nullable()->after('return_shipment_method');
            $table->string('return_shipment_tracking')->nullable()->after('return_shipment_officer');
            $table->dateTime('return_shipment_scheduled_at')->nullable()->after('return_shipment_tracking');
            $table->text('return_shipment_notes')->nullable()->after('return_shipment_scheduled_at');
            $table->dateTime('return_shipment_submitted_at')->nullable()->after('return_shipment_notes');
            $table->string('return_shipment_status')->nullable()->after('return_shipment_submitted_at');
            $table->json('return_shipment_timeline')->nullable()->after('return_shipment_status');
            $table->decimal('return_shipping_cost', 15, 0)->nullable()->after('return_shipment_timeline');
            $table->string('return_shipping_proof_path')->nullable()->after('return_shipping_cost');
            $table->timestamp('collection_arrived_at')->nullable()->after('return_shipping_proof_path');
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn([
                'return_shipment_method',
                'return_shipment_officer',
                'return_shipment_tracking',
                'return_shipment_scheduled_at',
                'return_shipment_notes',
                'return_shipment_submitted_at',
                'return_shipment_status',
                'return_shipment_timeline',
                'return_shipping_cost',
                'return_shipping_proof_path',
                'collection_arrived_at',
            ]);
        });
    }
};
