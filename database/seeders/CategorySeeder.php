<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Wedding Makeup', 'base_price' => 500000, 'duration_minutes' => 90],
            ['name' => 'Engagement Makeup', 'base_price' => 180000, 'duration_minutes' => 60],
            ['name' => 'Graduation', 'base_price' => 140000, 'duration_minutes' => 50],
            ['name' => 'Yearbook Makeup', 'base_price' => 100000, 'duration_minutes' => 45],
            ['name' => 'Traditional Makeup', 'base_price' => 150000, 'duration_minutes' => 60],
            ['name' => 'Family Bride', 'base_price' => 125000, 'duration_minutes' => 45],
        ];

        foreach ($data as $item) {
            \App\Models\Category::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
