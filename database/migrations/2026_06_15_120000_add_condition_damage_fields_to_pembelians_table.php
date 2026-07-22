<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            // Pengecekan kondisi saat penerimaan
            $table->string('condition_check_status')->nullable()->after('received_at');
            $table->timestamp('condition_checked_at')->nullable()->after('condition_check_status');

            // Laporan kerusakan dari pembeli
            $table->json('arrival_damage_items')->nullable()->after('condition_checked_at');
            $table->json('arrival_damage_photos')->nullable()->after('arrival_damage_items');
            $table->text('arrival_damage_description')->nullable()->after('arrival_damage_photos');
            $table->string('arrival_damage_severity')->nullable()->after('arrival_damage_description');
            $table->json('packing_condition_photos')->nullable()->after('arrival_damage_severity');
            $table->json('courier_receipt_photos')->nullable()->after('packing_condition_photos');
            $table->timestamp('arrival_damage_reported_at')->nullable()->after('courier_receipt_photos');
            // Keputusan pengelola
            $table->string('arrival_damage_final_severity')->nullable()->after('arrival_damage_reported_at');
            $table->boolean('arrival_damage_severity_corrected')->default(false)->after('arrival_damage_final_severity');
            $table->decimal('arrival_damage_compensation_amount', 15, 0)->nullable()->after('arrival_damage_severity_corrected');
            $table->text('arrival_damage_manager_notes')->nullable()->after('arrival_damage_compensation_amount');
            $table->timestamp('arrival_damage_decided_at')->nullable()->after('arrival_damage_manager_notes');
            $table->string('arrival_damage_decided_by')->nullable()->after('arrival_damage_decided_at');

            // Refund manual
            $table->string('refund_bank_name')->nullable()->after('arrival_damage_decided_by');
            $table->string('refund_account_number')->nullable()->after('refund_bank_name');
            $table->string('refund_account_holder')->nullable()->after('refund_account_number');
            $table->timestamp('refund_bank_submitted_at')->nullable()->after('refund_account_holder');
            $table->decimal('refund_amount', 15, 0)->nullable()->after('refund_bank_submitted_at');
            $table->string('refund_transfer_proof_path')->nullable()->after('refund_amount');
            $table->date('refund_date')->nullable()->after('refund_transfer_proof_path');
            $table->text('refund_notes')->nullable()->after('refund_date');
            $table->timestamp('refund_processed_at')->nullable()->after('refund_notes');
            $table->string('refund_processed_by')->nullable()->after('refund_processed_at');

            // Riwayat penanganan kerusakan
            $table->json('damage_handling_timeline')->nullable()->after('refund_processed_by');
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn([
                'condition_check_status',
                'condition_checked_at',
                'arrival_damage_items',
                'arrival_damage_photos',
                'arrival_damage_description',
                'arrival_damage_severity',
                'packing_condition_photos',
                'courier_receipt_photos',
                'arrival_damage_reported_at',
                'arrival_damage_final_severity',
                'arrival_damage_severity_corrected',
                'arrival_damage_compensation_amount',
                'arrival_damage_manager_notes',
                'arrival_damage_decided_at',
                'arrival_damage_decided_by',
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
                'damage_handling_timeline',
            ]);
        });
    }
};
