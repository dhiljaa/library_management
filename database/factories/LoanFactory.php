<?php

namespace Database\Factories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition()
    {
        $statuses = ['pending', 'approved', 'borrowed', 'returned', 'overdue'];
        $status = $this->faker->randomElement($statuses);

        $borrowed_at = null;
        $returned_at = null;

        if (in_array($status, ['approved', 'borrowed', 'returned', 'overdue'])) {
            $borrowed_at = Carbon::now()->subDays(rand(1, 30));
        }

        if ($status === 'returned' && $borrowed_at) {
            $returned_at = (clone $borrowed_at)->addDays(rand(1, 14));
        }

        if ($status === 'overdue' && $borrowed_at) {
            $returned_at = null; // belum dikembalikan tapi sudah lewat batas waktu
        }

        return [
            // user_id dan book_id akan di-set manual di seeder
            'borrowed_at' => $borrowed_at,
            'returned_at' => $returned_at,
            'status' => $status,
            'penalty' => 0,
            'is_penalty_paid' => false,
        ];
    }
}
