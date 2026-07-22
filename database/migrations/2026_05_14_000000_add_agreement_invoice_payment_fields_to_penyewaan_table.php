<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            if (! Schema::hasColumn('penyewaan', 'agreement_document_path')) {
                $table->string('agreement_document_path')->nullable()->after('revision_notes');
            }
            if (! Schema::hasColumn('penyewaan', 'invoice_document_path')) {
                $table->string('invoice_document_path')->nullable()->after('agreement_document_path');
            }
            if (! Schema::hasColumn('penyewaan', 'signed_agreement_path')) {
                $table->string('signed_agreement_path')->nullable()->after('invoice_document_path');
            }
            if (! Schema::hasColumn('penyewaan', 'signed_agreement_status')) {
                $table->enum('signed_agreement_status', ['pending', 'uploaded', 'accepted', 'rejected'])
                    ->default('pending')
                    ->after('signed_agreement_path');
            }
            if (! Schema::hasColumn('penyewaan', 'signed_agreement_review_notes')) {
                $table->text('signed_agreement_review_notes')->nullable()->after('signed_agreement_status');
            }
            if (! Schema::hasColumn('penyewaan', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'failed', 'expired'])
                    ->default('unpaid')
                    ->after('signed_agreement_review_notes');
            }
            if (! Schema::hasColumn('penyewaan', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn([
                'agreement_document_path',
                'invoice_document_path',
                'signed_agreement_path',
                'signed_agreement_status',
                'signed_agreement_review_notes',
                'payment_status',
                'payment_reference',
            ]);
        });
    }
};
