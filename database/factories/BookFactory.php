<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Fiksi', 'Non-Fiksi', 'Teknologi', 'Sejarah', 'Anak', 'Biografi', 'Bisnis', 'Sains'];

        return [
            'title' => fake()->catchPhrase(), // Lebih mirip judul buku
            'author' => fake()->name(),
            'publisher' => fake()->company(),
            'year' => fake()->numberBetween(1990, date('Y')),
            'category' => fake()->randomElement($categories),
            'stock' => fake()->numberBetween(5, 50),
        ];
    }
}
