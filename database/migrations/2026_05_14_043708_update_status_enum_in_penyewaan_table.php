<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing status values to match new enum
        DB::table('penyewaan')->where('status', 'menunggu verifikasi')->update(['status' => 'waiting_payment']);
        DB::table('penyewaan')->where('status', 'disetujui')->update(['status' => 'waiting_payment']);
        DB::table('penyewaan')->where('status', 'dibatalkan')->update(['status' => 'cancelled']);
        DB::table('penyewaan')->where('status', 'ditolak')->update(['status' => 'rejected']);
        DB::table('penyewaan')->where('status', 'aktif')->update(['status' => 'active']);

        Schema::table('penyewaan', function (Blueprint $table) {
            $table->enum('status', ['waiting_payment', 'preparing_delivery', 'delivered', 'active', 'completed', 'cancelled', 'rejected'])
                  ->default('waiting_payment')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }
};
