<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MuaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Admin Utama
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin MUA',
                'password' => bcrypt('admin123'),
            ]
        );

        // Data Wilayah
        \App\Models\Location::firstOrCreate(['region_name' => 'Kota Tegal'], ['additional_price' => 10000]);
        \App\Models\Location::firstOrCreate(['region_name' => 'Kabupaten Tegal'], ['additional_price' => 20000]);

        // Data Paket (Default image kosong dulu agar kamu bisa upload via Admin)
        \App\Models\Category::firstOrCreate(['name' => 'Makeup Wisuda'], [
            'base_price' => 200000,
            'duration_minutes' => 120,
            'image' => 'portfolio/default.jpg'
        ]);
    }
}
