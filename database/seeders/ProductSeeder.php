<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'product_code' => 'LK001',
                'name' => 'Lukisan Replika Mona Lisa',
                'category' => 'lukisan_replika',
                'description' => 'Replika lukisan Mona Lisa karya Leonardo da Vinci dengan kualitas tinggi.',
                'price' => 500000,
                'stock' => 10,
                'weight' => 2.5,
            ],
            [
                'product_code' => 'LK002',
                'name' => 'Lukisan Replika The Starry Night',
                'category' => 'lukisan_replika',
                'description' => 'Replika lukisan The Starry Night karya Vincent van Gogh.',
                'price' => 450000,
                'stock' => 8,
                'weight' => 2.0,
            ],
            [
                'product_code' => 'MC001',
                'name' => 'T-Shirt Museum MK Lesmana',
                'category' => 'merchandise',
                'description' => 'Kaos bergambar museum dengan desain eksklusif.',
                'price' => 150000,
                'stock' => 25,
                'weight' => 0.3,
            ],
            [
                'product_code' => 'MC002',
                'name' => 'Mug Museum',
                'category' => 'merchandise',
                'description' => 'Mug keramik dengan logo museum.',
                'price' => 75000,
                'stock' => 30,
                'weight' => 0.5,
            ],
            [
                'product_code' => 'SV001',
                'name' => 'Souvenir Keychain',
                'category' => 'souvenir',
                'description' => 'Gantungan kunci dengan miniatur museum.',
                'price' => 25000,
                'stock' => 50,
                'weight' => 0.1,
            ],
            [
                'product_code' => 'SV002',
                'name' => 'Postcard Set',
                'category' => 'souvenir',
                'description' => 'Set kartu pos bergambar koleksi museum.',
                'price' => 50000,
                'stock' => 20,
                'weight' => 0.2,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
