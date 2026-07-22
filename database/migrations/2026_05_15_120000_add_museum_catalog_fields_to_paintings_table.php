<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paintings', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
            $table->string('category')->default('Lukisan')->after('artist');
            $table->string('year_created')->nullable()->after('description');
            $table->string('media')->nullable()->after('year_created');
            $table->string('dimensions')->nullable()->after('media');
            $table->unsignedInteger('sale_price')->nullable()->after('daily_rate');
            $table->json('gallery_paths')->nullable()->after('image_path');
            $table->text('extra_info')->nullable()->after('gallery_paths');
            $table->foreignId('koleksi_id')->nullable()->after('extra_info')
                ->constrained('koleksis')->nullOnDelete();
        });

        $rows = DB::table('paintings')->orderBy('id')->get();
        foreach ($rows as $row) {
            $base = Str::slug($row->title);
            $slug = $base !== '' ? "{$base}-{$row->id}" : "lukisan-{$row->id}";
            DB::table('paintings')->where('id', $row->id)->update([
                'slug' => $slug,
                'category' => 'Lukisan',
                'year_created' => null,
                'media' => 'Cat akrilik di kanvas',
                'dimensions' => '80 x 60 cm',
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('paintings', function (Blueprint $table) {
            $table->dropForeign(['koleksi_id']);
            $table->dropColumn([
                'slug',
                'category',
                'year_created',
                'media',
                'dimensions',
                'sale_price',
                'gallery_paths',
                'extra_info',
                'koleksi_id',
            ]);
        });
    }
};
