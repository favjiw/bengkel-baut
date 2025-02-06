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
        $categories = [
            ['name' => 'Ganti Oli Plus', 'price' => 150000, 'description' => 'Layanan ganti oli mesin plus pengecekan kondisi filter oli dan performa mesin.', 'hour' => 1],
            ['name' => 'Service Ringan', 'price' => 250000, 'description' => 'Pengecekan dan perawatan ringan, termasuk pembersihan throttle body, pengecekan kelistrikan, dan pengencangan baut-baut penting.', 'hour' => 2],
            ['name' => 'Service Lengkap', 'price' => 400000, 'description' => 'Perawatan menyeluruh mencakup pembersihan injektor, penggantian filter udara, pengecekan rem, serta penyetelan mesin untuk performa optimal.', 'hour' => 3],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
