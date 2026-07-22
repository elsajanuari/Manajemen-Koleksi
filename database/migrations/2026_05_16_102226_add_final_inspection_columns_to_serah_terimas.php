<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            // Pemeriksaan akhir oleh pengelola (setelah koleksi dikembalikan)
            if (! Schema::hasColumn('serah_terimas', 'final_inspection_at')) {
                $table->timestamp('final_inspection_at')->nullable()->after('collection_returned_at');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_inspection_by')) {
                $table->string('final_inspection_by')->nullable()->after('final_inspection_at');
            }

            // Checklist pemeriksaan akhir
            if (! Schema::hasColumn('serah_terimas', 'final_checklist_frame_safe')) {
                $table->boolean('final_checklist_frame_safe')->default(false)->after('final_inspection_by');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_checklist_no_tears')) {
                $table->boolean('final_checklist_no_tears')->default(false)->after('final_checklist_frame_safe');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_checklist_color_normal')) {
                $table->boolean('final_checklist_color_normal')->default(false)->after('final_checklist_no_tears');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_checklist_glass_safe')) {
                $table->boolean('final_checklist_glass_safe')->default(false)->after('final_checklist_color_normal');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_checklist_no_mold')) {
                $table->boolean('final_checklist_no_mold')->default(false)->after('final_checklist_glass_safe');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_checklist_packaging_safe')) {
                $table->boolean('final_checklist_packaging_safe')->default(false)->after('final_checklist_no_mold');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_checklist_matches_documentation')) {
                $table->boolean('final_checklist_matches_documentation')->default(false)->after('final_checklist_packaging_safe');
            }

            // Catatan & foto pemeriksaan akhir
            if (! Schema::hasColumn('serah_terimas', 'final_inspection_notes')) {
                $table->text('final_inspection_notes')->nullable()->after('final_checklist_matches_documentation');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_inspection_photo_path')) {
                $table->string('final_inspection_photo_path')->nullable()->after('final_inspection_notes');
            }

            // Hasil pemeriksaan
            if (! Schema::hasColumn('serah_terimas', 'has_damage')) {
                $table->boolean('has_damage')->nullable()->after('final_inspection_photo_path'); // null = belum diperiksa
            }

            // Detail kerusakan (diisi pengelola jika has_damage = true)
            if (! Schema::hasColumn('serah_terimas', 'final_damage_type')) {
                $table->string('final_damage_type')->nullable()->after('has_damage');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_damage_level')) {
                $table->enum('final_damage_level', ['ringan', 'sedang', 'berat'])->nullable()->after('final_damage_type');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_damage_cost')) {
                $table->unsignedBigInteger('final_damage_cost')->default(0)->after('final_damage_level');
            }
            if (! Schema::hasColumn('serah_terimas', 'final_damage_notes')) {
                $table->text('final_damage_notes')->nullable()->after('final_damage_cost');
            }
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $columns = [
                'final_inspection_at', 'final_inspection_by',
                'final_checklist_frame_safe', 'final_checklist_no_tears',
                'final_checklist_color_normal', 'final_checklist_glass_safe',
                'final_checklist_no_mold', 'final_checklist_packaging_safe',
                'final_checklist_matches_documentation',
                'final_inspection_notes', 'final_inspection_photo_path',
                'has_damage',
                'final_damage_type', 'final_damage_level',
                'final_damage_cost', 'final_damage_notes',
            ];
            $existing = array_filter($columns, fn($c) => Schema::hasColumn('serah_terimas', $c));
            if ($existing) $table->dropColumn(array_values($existing));
        });
    }
};