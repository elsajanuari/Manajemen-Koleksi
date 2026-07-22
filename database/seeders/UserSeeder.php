<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Puspita Anggraini',
                'email' => 'puspita.anggraini@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
            ],
            [
                'name' => 'Satria Wijaya Kusuma',
                'email' => 'satria.wijaya@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
            ],
            [
                'name' => 'Nadine Kirana',
                'email' => 'nadine.kirana@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
            ],
            [
                'name' => 'Bagas Rangin',
                'email' => 'bagas.rangin@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
            ],
            [
                'name' => 'Ajeng Sekar Kedaton',
                'email' => 'ajeng.sekar@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
            ],
            [
                'name' => 'Cakrawala Langit',
                'email' => 'cakrawala.langit@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
            ],
            [
                'name' => 'Kenanga Lestari',
                'email' => 'kenanga.lestari@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
            ],
            [
                'name' => 'Pranajaya Utama',
                'email' => 'pranajaya.utama@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
            ],
            [
                'name' => 'Gendis Langit Biru',
                'email' => 'gendis.langit@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
            ],
            [
                'name' => 'Bramantya Kawi',
                'email' => 'bramantya.kawi@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengguna',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}