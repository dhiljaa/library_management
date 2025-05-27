<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Notification;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    // Daftar semua peminjaman aktif dan yang sedang berlangsung user
    public function index()
    {
        $loans = auth()->user()->loans()
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
                    'status' => $loan->status,
                ];
            }),
        ]);
    }

    // Pinjam buku baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Cek apakah user sudah pinjam buku ini dan belum dikembalikan
        $already = auth()->user()->loans()
            ->where('book_id', $validated['book_id'])
            ->where('status', 'borrowed')
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
            'status' => 'borrowed',
            'returned_at' => null,
        ]);

        // Notifikasi untuk admin & staff
        Notification::create([
            'title' => 'New Book Loan',
            'message' => 'User ' . auth()->user()->name . ' borrowed the book with ID: ' . $loan->book_id,
            'is_read' => false,
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
                'status' => $loan->status,
            ],
        ], 201);
    }

    // Pengembalian buku
    public function return($id)
    {
        $loan = auth()->user()->loans()->findOrFail($id);

        if ($loan->status === 'returned') {
            return response()->json([
                'status' => 'error',
                'message' => 'Book already returned',
            ], 400);
        }

        $loan->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Book returned successfully',
        ]);
    }

    // Riwayat peminjaman yang sudah dikembalikan
    public function history()
    {
        $loans = auth()->user()->loans()
            ->where('status', 'returned')
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
                    'status' => $loan->status,
                ];
            }),
        ]);
    }
}
