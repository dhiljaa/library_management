<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = auth()->user()->loans()
            ->with('book') // agar data buku ikut muncul
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $loans->map(function ($loan) {
                return [
                    'id' => $loan->id,
                    'book_id' => $loan->book_id,
                    'user_id' => $loan->user_id,
                    'borrowed_at' => $loan->borrowed_at,
                    'returned_at' => $loan->returned_at,
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $already = auth()->user()->loans()
            ->where('book_id', $validated['book_id'])
            ->whereNull('returned_at')
            ->first();

        if ($already) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book already borrowed and not yet returned',
            ], 422);
        }

        $loan = auth()->user()->loans()->create([
            'book_id' => $validated['book_id'],
            'borrowed_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Book borrowed successfully',
            'data' => [
                'id' => $loan->id,
                'book_id' => $loan->book_id,
                'user_id' => $loan->user_id,
                'borrowed_at' => $loan->borrowed_at,
                'returned_at' => $loan->returned_at,
            ],
        ], 201);
    }

    public function return($id)
    {
        $loan = auth()->user()->loans()->findOrFail($id);

        if ($loan->returned_at) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book already returned',
            ], 400);
        }

        $loan->update([
            'returned_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Book returned successfully',
        ]);
    }

    public function history()
    {
        $loans = auth()->user()->loans()
            ->whereNotNull('returned_at')
            ->with('book')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $loans->map(function ($loan) {
                return [
                    'id' => $loan->id,
                    'book_id' => $loan->book_id,
                    'user_id' => $loan->user_id,
                    'borrowed_at' => $loan->borrowed_at,
                    'returned_at' => $loan->returned_at,
                ];
            }),
        ]);
    }
}
