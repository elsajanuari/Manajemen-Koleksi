<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            if (!Schema::hasColumn('penyewaan', 'verification_notes')) {
                $table->text('verification_notes')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('penyewaan', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('verification_notes');
            }
            if (!Schema::hasColumn('penyewaan', 'revision_notes')) {
                $table->text('revision_notes')->nullable()->after('rejection_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            if (Schema::hasColumn('penyewaan', 'revision_notes')) {
                $table->dropColumn('revision_notes');
            }
            if (Schema::hasColumn('penyewaan', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('penyewaan', 'verification_notes')) {
                $table->dropColumn('verification_notes');
            }
        });
    }
};
