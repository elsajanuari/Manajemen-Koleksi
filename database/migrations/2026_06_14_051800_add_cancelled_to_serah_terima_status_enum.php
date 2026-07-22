<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE serah_terimas MODIFY serah_terima_status ENUM(
            'pending',
            'waiting_confirmation',
            'preparing_delivery',
            'in_delivery',
            'condition_checking',
            'waiting_document',
            'document_uploaded',
            'validated',
            'damage_reported',
            'damage_reviewed',
            'cancelled_due_to_damage',
            'cancelled'
        ) NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE serah_terimas MODIFY serah_terima_status ENUM(
            'pending',
            'waiting_confirmation',
            'preparing_delivery',
            'in_delivery',
            'condition_checking',
            'waiting_document',
            'document_uploaded',
            'validated',
            'damage_reported',
            'damage_reviewed',
            'cancelled_due_to_damage'
        ) NULL DEFAULT 'pending'");
    }
};