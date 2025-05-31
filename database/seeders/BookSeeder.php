<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori sudah ada minimal 3
        if (Category::count() === 0) {
            Category::factory()->count(3)->create();
        }

      $judulBukuIndonesia = [
    'Laskar Pelangi', 'Bumi Manusia', 'Ayat-Ayat Cinta', 'Negeri 5 Menara',
    'Perahu Kertas', 'Supernova', 'Sang Pemimpi', '5 cm', 'Dilan 1990', 'Kambing Jantan',
    'Ronggeng Dukuh Paruk', 'Cantik Itu Luka', 'Amba', 'Gadis Kretek', 'Saman',
    'Sitti Nurbaya', 'Olenka', 'Manusia Setengah Salmon', 'Rindu', 'Madilog',
    'Sebuah Seni untuk Bersikap Bodo Amat', 'Bulan', 'Cinta di Dalam Gelas',
    'Pulang', 'Hujan', 'Ayah', 'Perempuan yang Menangis di Balik Jendela'
];

$penerbitIndonesia = [
    'Gramedia Pustaka Utama', 'Mizan', 'Bentang Pustaka', 'Elex Media Komputindo',
    'Kompas Gramedia', 'Penerbit Republika', 'Narasi', 'Penerbit Bukune',
    'Penerbit Lentera Dipantara', 'Penerbit GagasMedia', 'Penerbit Deepublish',
    'Penerbit Qanita', 'Penerbit Media Kita', 'Penerbit Noura Books',
    'Penerbit Pustaka Alvabet', 'Penerbit Indigo', 'Penerbit KPG (Kepustakaan Populer Gramedia)'
];

        // Buat 10 buku dari judul dan penerbit Indonesia
        foreach ($judulBukuIndonesia as $judul) {
            $category = Category::inRandomOrder()->first();

            Book::factory()->create([
                'title' => $judul,
                'author' => fake('id_ID')->name(),
                'publisher' => $penerbitIndonesia[array_rand($penerbitIndonesia)],
                'published_year' => fake()->numberBetween(1990, 2023),
                'category_id' => $category->id,
                'description' => fake()->paragraph(),
                'quantity' => fake()->numberBetween(1, 20),
                'borrowed_count' => fake()->numberBetween(0, 100),
                'image_url' => fake()->imageUrl(200, 300, 'books', true),
            ]);
        }

        // Buat 15 buku random tambahan dengan factory
        Book::factory()->count(15)->create();
    }
}
