<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $kategoriIndonesia = [
            'Sastra',
            'Pendidikan',
            'Agama',
            'Teknologi',
            'Sejarah',
            'Anak-anak',
            'Bisnis',
            'Kesehatan',
            'Hobi & Minat',
            'Fiksi',
            'Non-Fiksi',
            'Budaya',
        ];

        foreach ($kategoriIndonesia as $kategori) {
            Category::updateOrCreate(['name' => $kategori]);
        }
    }
}
