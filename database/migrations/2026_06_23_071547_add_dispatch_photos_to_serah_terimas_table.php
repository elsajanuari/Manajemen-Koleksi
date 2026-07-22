<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->string('dispatch_front_photo')->nullable()->after('shipped_at');
            $table->string('dispatch_back_photo')->nullable()->after('dispatch_front_photo');
            $table->json('dispatch_packing_photos')->nullable()->after('dispatch_back_photo');
            $table->string('dispatch_video_path')->nullable()->after('dispatch_packing_photos');
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn([
                'dispatch_front_photo',
                'dispatch_back_photo',
                'dispatch_packing_photos',
                'dispatch_video_path',
            ]);
        });
    }
};