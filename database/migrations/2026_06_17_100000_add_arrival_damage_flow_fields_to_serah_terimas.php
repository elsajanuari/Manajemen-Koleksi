<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->string('condition_video')->nullable()->after('condition_back_photo');
            $table->string('damage_video_path')->nullable()->after('condition_video');
            $table->json('arrival_damage_items')->nullable()->after('arrival_damage_checklist');
            $table->json('packing_condition_photos')->nullable()->after('arrival_damage_description');
            $table->json('courier_receipt_photos')->nullable()->after('packing_condition_photos');
            $table->string('arrival_damage_buyer_decision', 20)->nullable()->after('arrival_damage_tenant_decision');
            $table->string('arrival_damage_final_severity', 20)->nullable()->after('arrival_damage_buyer_decision');
            $table->boolean('arrival_damage_severity_corrected')->default(false)->after('arrival_damage_final_severity');
            $table->decimal('arrival_damage_compensation_amount', 15, 0)->nullable()->after('arrival_damage_manager_notes');
            $table->string('arrival_damage_decided_by')->nullable()->after('arrival_damage_decided_at');
            $table->json('damage_handling_timeline')->nullable()->after('arrival_damage_decided_by');

            $table->string('refund_bank_name')->nullable()->after('damage_handling_timeline');
            $table->string('refund_account_number')->nullable()->after('refund_bank_name');
            $table->string('refund_account_holder')->nullable()->after('refund_account_number');
            $table->timestamp('refund_bank_submitted_at')->nullable()->after('refund_account_holder');
            $table->decimal('refund_amount', 15, 0)->nullable()->after('refund_bank_submitted_at');
            $table->string('refund_transfer_proof_path')->nullable()->after('refund_amount');
            $table->date('refund_date')->nullable()->after('refund_transfer_proof_path');
            $table->text('refund_notes')->nullable()->after('refund_date');
            $table->timestamp('refund_processed_at')->nullable()->after('refund_notes');
            $table->string('refund_processed_by')->nullable()->after('refund_processed_at');

            $table->decimal('return_shipping_cost', 15, 0)->nullable()->after('return_shipment_timeline');
            $table->string('return_shipping_proof_path')->nullable()->after('return_shipping_cost');
        });

        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->string('arrival_damage_manager_decision', 50)->nullable()->change();
            $table->string('arrival_damage_tenant_decision', 20)->nullable()->change();
            $table->string('arrival_damage_severity', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn([
                'condition_video',
                'damage_video_path',
                'arrival_damage_items',
                'packing_condition_photos',
                'courier_receipt_photos',
                'arrival_damage_buyer_decision',
                'arrival_damage_final_severity',
                'arrival_damage_severity_corrected',
                'arrival_damage_compensation_amount',
                'arrival_damage_decided_by',
                'damage_handling_timeline',
                'refund_bank_name',
                'refund_account_number',
                'refund_account_holder',
                'refund_bank_submitted_at',
                'refund_amount',
                'refund_transfer_proof_path',
                'refund_date',
                'refund_notes',
                'refund_processed_at',
                'refund_processed_by',
                'return_shipping_cost',
                'return_shipping_proof_path',
            ]);
        });
    }
};
