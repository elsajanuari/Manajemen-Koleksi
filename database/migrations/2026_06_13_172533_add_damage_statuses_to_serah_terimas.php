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
            'returned',
            'damage_reported',
            'damage_reviewed',
            'cancelled_due_to_damage'
        ) DEFAULT 'waiting_handover'");
    }

    public function down(): void
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
    }
};
