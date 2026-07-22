<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            if (! Schema::hasColumn('serah_terimas', 'collection_arrived_at')) {
                $table->timestamp('collection_arrived_at')->nullable()->after('return_shipment_submitted_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            if (Schema::hasColumn('serah_terimas', 'collection_arrived_at')) {
                $table->dropColumn('collection_arrived_at');
            }
        });
    }
};