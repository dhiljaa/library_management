<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class BookFactory extends Factory
{
    public function definition()
    {
        // Pakai faker locale Indonesia
        $faker = \Faker\Factory::create('id_ID');

        $category = Category::inRandomOrder()->first();

        // List judul buku Indonesia lebih banyak dan variatif
        $judulBukuIndonesia = [
            'Laskar Pelangi', 'Bumi Manusia', 'Ayat-Ayat Cinta', 'Negeri 5 Menara',
            'Perahu Kertas', 'Supernova', 'Sang Pemimpi', '5 cm', 'Dilan 1990', 'Kambing Jantan',
            'Ronggeng Dukuh Paruk', 'Cantik Itu Luka', 'Amba', 'Gadis Kretek', 'Saman',
            'Sitti Nurbaya', 'Olenka', 'Manusia Setengah Salmon', 'Rindu', 'Madilog',
            'Sebuah Seni untuk Bersikap Bodo Amat', 'Bulan', 'Cinta di Dalam Gelas',
            'Pulang', 'Hujan', 'Ayah', 'Perempuan yang Menangis di Balik Jendela'
        ];

        // List penerbit buku Indonesia lengkap
        $penerbitIndonesia = [
            'Gramedia Pustaka Utama', 'Mizan', 'Bentang Pustaka', 'Elex Media Komputindo',
            'Kompas Gramedia', 'Penerbit Republika', 'Narasi', 'Penerbit Bukune',
            'Penerbit Lentera Dipantara', 'Penerbit GagasMedia', 'Penerbit Deepublish',
            'Penerbit Qanita', 'Penerbit Media Kita', 'Penerbit Noura Books',
            'Penerbit Pustaka Alvabet', 'Penerbit Indigo', 'Penerbit KPG (Kepustakaan Populer Gramedia)'
        ];

        return [
            'title' => $faker->randomElement($judulBukuIndonesia),
            'author' => $faker->name(),
            'publisher' => $faker->randomElement($penerbitIndonesia),
            'published_year' => $faker->numberBetween(1901, date('Y')),
            'category_id' => $category ? $category->id : Category::factory(),
            'description' => $faker->paragraph(),
            'quantity' => $faker->numberBetween(1, 20),
            'borrowed_count' => $faker->numberBetween(0, 100),
            'image_url' => $faker->imageUrl(200, 300, 'books', true),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($book) {
            \App\Models\Review::factory()->count(rand(3, 7))->create([
                'book_id' => $book->id,
            ]);
        });
    }
}
