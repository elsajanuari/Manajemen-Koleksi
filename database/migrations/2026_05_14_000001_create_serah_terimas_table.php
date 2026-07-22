<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serah_terimas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->unique()->constrained('penyewaan')->cascadeOnDelete();
            $table->string('document_number')->unique();
            $table->enum('handover_status', [
                'waiting_handover',
                'preparing_delivery',
                'in_delivery',
                'delivered',
                'handover_completed',
            ])->default('waiting_handover');
            $table->string('delivery_method')->nullable();
            $table->string('delivery_location')->nullable();
            $table->string('recipient_name')->nullable();
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->string('handover_document_path')->nullable();
            $table->string('signed_handover_path')->nullable();
            $table->text('initial_condition_note')->nullable();
            $table->string('initial_condition_photo_path')->nullable();
            $table->boolean('checklist_frame_safe')->default(false);
            $table->boolean('checklist_no_tears')->default(false);
            $table->boolean('checklist_color_normal')->default(false);
            $table->boolean('checklist_glass_safe')->default(false);
            $table->boolean('checklist_no_mold')->default(false);
            $table->boolean('checklist_matches_documentation')->default(false);
            $table->text('condition_notes')->nullable();
            $table->text('tenant_notes')->nullable();
            $table->string('received_condition_photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('serah_terimas');
    }
};
