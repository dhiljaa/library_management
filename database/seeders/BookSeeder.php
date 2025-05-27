<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada kategori dulu
        if (Category::count() === 0) {
            Category::factory()->count(3)->create(); // buat 3 kategori contoh
        }

        // Buat 20 buku dengan factory
        Book::factory()->count(20)->create();
    }
}
