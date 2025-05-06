<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Book;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $books = Book::all();

        foreach ($users as $user) {
            Review::create([
                'user_id' => $user->id,
                'book_id' => $books->random()->id,
                'rating' => rand(3, 5),
                'comment' => fake()->sentence(),
            ]);
        }
    }
}
