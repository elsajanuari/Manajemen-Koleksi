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
        Schema::table('pembelians', function (Blueprint $table) {
            if (!Schema::hasColumn('pembelians', 'dispatch_front_photo')) {
                $table->text('dispatch_front_photo')->nullable()->after('delivery_notes');
            }
            if (!Schema::hasColumn('pembelians', 'dispatch_back_photo')) {
                $table->text('dispatch_back_photo')->nullable()->after('dispatch_front_photo');
            }
            if (!Schema::hasColumn('pembelians', 'dispatch_packing_photos')) {
                $table->json('dispatch_packing_photos')->nullable()->after('dispatch_back_photo');
            }
            if (!Schema::hasColumn('pembelians', 'dispatch_video_path')) {
                $table->text('dispatch_video_path')->nullable()->after('dispatch_packing_photos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumnIfExists('dispatch_front_photo');
            $table->dropColumnIfExists('dispatch_back_photo');
            $table->dropColumnIfExists('dispatch_packing_photos');
            $table->dropColumnIfExists('dispatch_video_path');
        });
    }
};
