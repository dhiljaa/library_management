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
        // Jangan truncate users & books di sini kalau sudah ada datanya,
        // agar tidak hapus data lain yg mungkin penting.
        // DB::table('users')->truncate();
        // DB::table('books')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Pastikan user sudah ada, kalau belum buat dulu
        if (User::count() === 0) {
            User::factory()->count(5)->create();
        }

        // Pastikan book sudah ada, kalau belum buat dulu
        if (Book::count() === 0) {
            Book::factory()->count(10)->create();
        }

        $users = User::all();
        $books = Book::all();

        // Buat 20 pinjaman acak dengan status valid
        for ($i = 0; $i < 20; $i++) {
            $statuses = ['pending', 'approved', 'borrowed', 'returned', 'overdue'];
            $status = $statuses[array_rand($statuses)];

            $borrowedAt = null;
            $returnedAt = null;

            if (in_array($status, ['approved', 'borrowed', 'returned', 'overdue'])) {
                $borrowedAt = Carbon::now()->subDays(rand(1, 30));
            }

            if ($status === 'returned' && $borrowedAt) {
                $returnedAt = (clone $borrowedAt)->addDays(rand(1, 14));
            }

            if ($status === 'overdue' && $borrowedAt) {
                $returnedAt = null; // belum dikembalikan tapi sudah lewat batas waktu
            }

            Loan::factory()->create([
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
                'status' => $status,
                'borrowed_at' => $borrowedAt,
                'returned_at' => $returnedAt,
                'penalty' => 0,
                'is_penalty_paid' => false,
            ]);
        }
    }
}
