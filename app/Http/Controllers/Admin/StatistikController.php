<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;

class StatistikController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $totalLoans = Loan::count();
        $activeLoans = Loan::where('status', 'borrowed')->count();

        return response()->json([
            'total_books' => $totalBooks,
            'total_users' => $totalUsers,
            'total_loans' => $totalLoans,
            'active_loans' => $activeLoans,
        ]);
    }
}
