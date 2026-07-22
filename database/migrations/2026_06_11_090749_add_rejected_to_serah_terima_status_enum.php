<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN serah_terima_status ENUM(
            'pending',
            'waiting_confirmation',
            'preparing_delivery',
            'in_delivery',
            'waiting_document',
            'document_uploaded',
            'validated',
            'rejected'
        ) NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN serah_terima_status ENUM(
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