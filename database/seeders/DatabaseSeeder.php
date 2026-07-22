<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(AdminSeeder::class);
        $this->call(PaintingSeeder::class);
        $this->call(KoleksiSeeder::class);
        $this->call(KondisiKoleksiSeeder::class);
        $this->call(PerawatanKoleksiSeeder::class);
        $this->call(CatalogMuseumLinkSeeder::class);
    }
}
