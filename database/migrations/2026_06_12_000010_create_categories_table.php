<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $defaultCategories = ['lukisan', 'buku'];

        foreach ($defaultCategories as $name) {
            DB::table('categories')->insert([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Schema::hasTable('koleksis')) {
            $categories = DB::table('koleksis')
                ->select('kategori')
                ->distinct()
                ->pluck('kategori')
                ->filter(fn ($value) => $value !== null && $value !== '' && ! in_array($value, $defaultCategories, true))
                ->unique();

            foreach ($categories as $name) {
                DB::table('categories')->insert([
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
