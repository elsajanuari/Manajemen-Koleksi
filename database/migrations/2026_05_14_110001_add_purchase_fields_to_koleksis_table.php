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
        Schema::table('koleksis', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable()->after('foto');
            $table->string('artist_name')->nullable()->after('price');
            $table->string('size')->nullable()->after('artist_name');
            $table->string('media')->nullable()->after('size');
            $table->string('condition')->default('baik')->after('media');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            $table->dropColumn(['price', 'artist_name', 'size', 'media', 'condition']);
        });
    }
};
