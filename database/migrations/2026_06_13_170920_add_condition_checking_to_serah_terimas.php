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
        \DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN handover_status ENUM(
            'waiting_handover',
            'preparing_delivery',
            'in_delivery',
            'delivered',
            'condition_checking',
            'handover_completed',
            'waiting_return_signature',
            'returned'
        ) DEFAULT 'waiting_handover'");

        \DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN serah_terima_status ENUM(
            'pending',
            'waiting_confirmation',
            'preparing_delivery',
            'in_delivery',
            'condition_checking',
            'waiting_document',
            'document_uploaded',
            'validated'
        ) NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN handover_status ENUM(
            'waiting_handover',
            'preparing_delivery',
            'in_delivery',
            'delivered',
            'handover_completed',
            'waiting_return_signature',
            'returned'
        ) DEFAULT 'waiting_handover'");

        \DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN serah_terima_status ENUM(
            'pending',
            'waiting_confirmation',
            'preparing_delivery',
            'in_delivery',
            'waiting_document',
            'document_uploaded',
            'validated'
        ) NULL DEFAULT 'pending'");
    }
};
