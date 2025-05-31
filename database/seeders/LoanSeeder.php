<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;

class LoanSeeder extends Seeder
{
    public function run()
    {
        // Matikan foreign key checks supaya truncate aman
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('loans')->truncate();
        DB::table('books')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Buat user dan book baru
        User::factory()->count(5)->create();
        Book::factory()->count(10)->create();

        // Ambil data user dan book
        $users = User::all();
        $books = Book::all();

        // Pinjaman manual dengan status berbeda
        Loan::create([
            'user_id' => $users->random()->id,
            'book_id' => $books->random()->id,
            'borrowed_at' => Carbon::now()->subDays(3),
            'returned_at' => null,
            'status' => 'borrowed',
        ]);

        Loan::create([
            'user_id' => $users->random()->id,
            'book_id' => $books->random()->id,
            'borrowed_at' => Carbon::now()->subDays(10),
            'returned_at' => Carbon::now()->subDays(5),
            'status' => 'returned',
        ]);

        // Buat 13 pinjaman acak, pakai user_id dan book_id dari data yang sudah ada
        for ($i = 0; $i < 13; $i++) {
            Loan::factory()->create([
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
            ]);
        }
    }
}
