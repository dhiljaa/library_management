<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Loan;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // Senin
        $endOfWeek = Carbon::now()->endOfWeek();     // Minggu

        $weekly_borrowers = Loan::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->distinct('user_id')
            ->count('user_id');

        $data = [
            'total_books' => Book::count(),
            'total_users' => User::count(),
            'total_loans' => Loan::count(),
            'active_loans' => Loan::whereNull('returned_at')->count(),
            'weekly_borrowers' => $weekly_borrowers,
        ];

        return view('admin.dashboard', $data);
    }
}
