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

        // Buku populer (berdasarkan jumlah peminjaman)
        $popular_books = Book::withCount(['loans' => function ($query) {
                $query->whereIn('status', ['borrowed', 'returned']);
            }])
            ->having('loans_count', '>', 0)  // Filter hanya buku pernah dipinjam
            ->orderByDesc('loans_count')
            ->paginate(5, ['id', 'title', 'author', 'image_url']);

        // Buku top rating (berdasarkan rata-rata rating review)
        $top_rated_books = Book::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->having('reviews_count', '>', 0) // Hanya yang punya review
            ->orderByDesc('reviews_avg_rating')
            ->paginate(5, ['id', 'title', 'author', 'image_url']);

        $data = [
            'total_books' => Book::count(),
            'total_users' => User::count(),
            'total_loans' => Loan::count(),
            'active_loans' => $active_loans,
            'weekly_borrowers' => $weekly_borrowers,
            'popular_books' => $popular_books,
            'top_rated_books' => $top_rated_books,
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

        $popular_books = Book::withCount(['loans' => function ($query) {
                $query->whereIn('status', ['borrowed', 'returned']);
            }])
            ->having('loans_count', '>', 0)
            ->orderByDesc('loans_count')
            ->take(5)
            ->get(['id', 'title', 'author', 'image_url']);

        $top_rated_books = Book::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->having('reviews_count', '>', 0)
            ->orderByDesc('reviews_avg_rating')
            ->take(5)
            ->get(['id', 'title', 'author', 'image_url']);

        return response()->json([
            'total_books' => Book::count(),
            'total_users' => User::count(),
            'total_loans' => Loan::count(),
            'active_loans' => $active_loans,
            'weekly_borrowers' => $weekly_borrowers,
            'popular_books' => $popular_books,
            'top_rated_books' => $top_rated_books,
        ]);
    }
}
