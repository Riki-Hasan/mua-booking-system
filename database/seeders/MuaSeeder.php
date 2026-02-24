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
        // Data Wilayah & Ongkir
        \App\Models\Location::create(['region_name' => 'Kota Tegal', 'additional_price' => 10000]);
        \App\Models\Location::create(['region_name' => 'Kabupaten Tegal', 'additional_price' => 20000]);
        \App\Models\Location::create(['region_name' => 'Kabupaten Brebes', 'additional_price' => 25000]);

        // Data Paket & Durasi
        \App\Models\Category::create([
            'name' => 'Makeup Wisuda', 
            'base_price' => 200000, 
            'duration_minutes' => 120 // 2 Jam
        ]);
        \App\Models\Category::create([
            'name' => 'Makeup Wedding', 
            'base_price' => 1500000, 
            'duration_minutes' => 240 // 4 Jam
        ]);
    }
}
