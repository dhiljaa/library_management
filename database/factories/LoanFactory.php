<?php

namespace Database\Factories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        $statuses = ['borrowed', 'returned'];
        $status = $this->faker->randomElement($statuses);

        if ($status === 'returned') {
            $borrowed_at = Carbon::now()->subDays(rand(15, 30));
            $returned_at = (clone $borrowed_at)->addDays(rand(1, 14));
        } else {
            $borrowed_at = Carbon::now()->subDays(rand(1, 14));
            $returned_at = null;
        }

        return [
            // user_id dan book_id harus diberikan di seeder supaya tidak buat data baru
            'borrowed_at' => $borrowed_at,
            'returned_at' => $returned_at,
            'status' => $status,
        ];
    }
}
