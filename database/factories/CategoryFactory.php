<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
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

        return [
            'name' => $this->faker->unique()->randomElement($kategoriIndonesia),
        ];
    }
}
