<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN handover_status ENUM(
            'waiting_handover',
            'preparing_delivery',
            'in_delivery',
            'delivered',
            'handover_completed',
            'waiting_return_signature',
            'return_document_uploaded',
            'returned'
        ) NOT NULL DEFAULT 'waiting_handover'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN handover_status ENUM(
            'waiting_handover',
            'preparing_delivery',
            'in_delivery',
            'delivered',
            'handover_completed'
        ) NOT NULL DEFAULT 'waiting_handover'");
    }
};