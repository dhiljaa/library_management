<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        // Status hanya 'borrowed' atau 'returned' sesuai migrasi
        $statuses = ['borrowed', 'returned'];
        $status = $this->faker->randomElement($statuses);

        if ($status === 'returned') {
            // Pinjam antara 15-30 hari lalu, dikembalikan 1-14 hari lalu
            $borrowed_at = Carbon::now()->subDays(rand(15, 30));
            $returned_at = Carbon::now()->subDays(rand(1, 14));
        } else {
            // Pinjam antara 1-14 hari lalu, belum dikembalikan
            $borrowed_at = Carbon::now()->subDays(rand(1, 14));
            $returned_at = null;
        }

        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'borrowed_at' => $borrowed_at,
            'returned_at' => $returned_at,
            'status' => $status,
        ];
    }
}
