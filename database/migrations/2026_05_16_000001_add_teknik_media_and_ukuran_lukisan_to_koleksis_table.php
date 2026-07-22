<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('koleksis', 'teknik_media') || ! Schema::hasColumn('koleksis', 'ukuran_lukisan')) {
            Schema::table('koleksis', function (Blueprint $table) {
                if (! Schema::hasColumn('koleksis', 'teknik_media')) {
                    $table->string('teknik_media')->nullable()->after('tahun');
                }
                if (! Schema::hasColumn('koleksis', 'ukuran_lukisan')) {
                    $table->string('ukuran_lukisan')->nullable()->after('teknik_media');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            if (Schema::hasColumn('koleksis', 'teknik_media')) {
                $table->dropColumn('teknik_media');
            }
            if (Schema::hasColumn('koleksis', 'ukuran_lukisan')) {
                $table->dropColumn('ukuran_lukisan');
            }
        });
    }
};
