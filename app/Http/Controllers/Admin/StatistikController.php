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
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weekly_borrowers = Loan::whereBetween('borrowed_at', [$startOfWeek, $endOfWeek])
            ->distinct('user_id')
            ->count('user_id');

        $active_loans = Loan::where('status', 'borrowed')
            ->whereNull('returned_at')
            ->count();

        $data = [
            'total_books' => Book::count(),
            'total_users' => User::count(),
            'total_loans' => Loan::count(),
            'active_loans' => $active_loans,
            'weekly_borrowers' => $weekly_borrowers,
        ];

        return view('admin.dashboard', $data);
    }

    public function apiIndex()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weekly_borrowers = Loan::whereBetween('borrowed_at', [$startOfWeek, $endOfWeek])
            ->distinct('user_id')
            ->count('user_id');

        $active_loans = Loan::where('status', 'borrowed')
            ->whereNull('returned_at')
            ->count();

        return response()->json([
            'total_books' => Book::count(),
            'total_users' => User::count(),
            'total_loans' => Loan::count(),
            'active_loans' => $active_loans,
            'weekly_borrowers' => $weekly_borrowers,
        ]);
    }
}
