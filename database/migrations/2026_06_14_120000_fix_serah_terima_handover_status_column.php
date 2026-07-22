<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * handover_status sempat diubah kembali ke ENUM terbatas sehingga
     * nilai alur pengembalian (return_shipment_submitted, dll.) gagal disimpan.
     * Kembalikan ke VARCHAR agar semua status workflow bisa dipakai.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN handover_status VARCHAR(50) NOT NULL DEFAULT 'waiting_handover'");

        DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN arrival_damage_manager_decision VARCHAR(50) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN handover_status ENUM(
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
        ) NOT NULL DEFAULT 'waiting_handover'");

        DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN arrival_damage_manager_decision ENUM(
            'setuju_lanjut',
            'tolak_lanjut',
            'setuju_batal'
        ) NULL");
    }
};
