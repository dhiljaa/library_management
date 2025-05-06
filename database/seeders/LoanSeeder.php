<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $books = Book::all();

        foreach ($users as $user) {
            Loan::create([
                'user_id' => $user->id,
                'book_id' => $books->random()->id,
                'borrowed_at' => now()->subDays(rand(1, 10)),
                'returned_at' => rand(0, 1) ? now() : null,
                'status' => rand(0, 1) ? 'returned' : 'borrowed',
            ]);
        }
    }
}
