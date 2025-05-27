<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;

class LoanSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada User dan Book dulu, jika belum ada buat dummy sederhana:
        $user = User::first() ?? User::factory()->create();
        $book = Book::first() ?? Book::factory()->create();

        // Contoh 1: Peminjaman aktif (belum dikembalikan)
        Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => Carbon::now()->subDays(3),
            'returned_at' => null,  // belum dikembalikan
            'status' => 'borrowed',
        ]);

        // Contoh 2: Peminjaman sudah dikembalikan
        Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => Carbon::now()->subDays(10),
            'returned_at' => Carbon::now()->subDays(5),  // sudah dikembalikan
            'status' => 'returned',
        ]);

        // Tambahan contoh beberapa data loan lain
        Loan::factory()->count(13)->create(); // total jadi 15 peminjaman
    }
}
