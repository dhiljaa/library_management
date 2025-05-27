<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class BookFactory extends Factory
{
    public function definition()
    {
        // Ambil kategori random dari tabel category
        $category = Category::inRandomOrder()->first();

        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'publisher' => $this->faker->company(),
            'published_year' => $this->faker->numberBetween(1900, date('Y')),
            'category_id' => $category ? $category->id : Category::factory(), // fallback buat category baru kalau kosong
            'description' => $this->faker->paragraph(),
            'quantity' => $this->faker->numberBetween(1, 20),
            'borrowed_count' => $this->faker->numberBetween(0, 100),
            'image_url' => $this->faker->imageUrl(200, 300, 'books', true),
        ];
    }
}
