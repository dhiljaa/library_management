<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'category' => 'Novel',
                'description' => 'Sebuah kisah inspiratif tentang pendidikan di Belitung.',
                'published_year' => 2005,
                'quantity' => 10,
                'borrowed_count' => 50,
                'image_url' => 'https://upload.wikimedia.org/wikipedia/en/4/49/Laskar_Pelangi.jpg',
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'category' => 'Self-help',
                'description' => 'A guide to building good habits and breaking bad ones.',
                'published_year' => 2018,
                'quantity' => 15,
                'borrowed_count' => 30,
                'image_url' => 'https://images-na.ssl-images-amazon.com/images/I/51-nXsSRfZL._SX328_BO1,204,203,200_.jpg',
            ],
            [
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'author' => 'J.K. Rowling',
                'category' => 'Fantasy',
                'description' => 'The first book in the Harry Potter series.',
                'published_year' => 1997,
                'quantity' => 20,
                'borrowed_count' => 100,
                'image_url' => 'https://upload.wikimedia.org/wikipedia/en/6/6b/Harry_Potter_and_the_Philosopher%27s_Stone_Book_Cover.jpg',
            ],
            [
                'title' => 'The Alchemist',
                'author' => 'Paulo Coelho',
                'category' => 'Novel',
                'description' => 'A novel about a shepherd\'s journey to find treasure.',
                'published_year' => 1988,
                'quantity' => 12,
                'borrowed_count' => 40,
                'image_url' => 'https://upload.wikimedia.org/wikipedia/en/c/c4/TheAlchemist.jpg',
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'category' => 'Classic',
                'description' => 'A novel about racial injustice in the Deep South.',
                'published_year' => 1960,
                'quantity' => 18,
                'borrowed_count' => 60,
                'image_url' => 'https://upload.wikimedia.org/wikipedia/en/7/79/To_Kill_a_Mockingbird.JPG',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
