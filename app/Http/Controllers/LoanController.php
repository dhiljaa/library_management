<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        // Tampilkan semua pinjaman user termasuk buku terkait
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
                    'status' => $loan->status, // tambah status supaya jelas
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Cek apakah buku sudah dipinjam dan belum dikembalikan (status 'borrowed')
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

        // Simpan pinjaman baru dengan status 'borrowed'
        $loan = auth()->user()->loans()->create([
            'book_id' => $validated['book_id'],
            'borrowed_at' => now(),
            'status' => 'borrowed',
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

    public function return($id)
    {
        $loan = auth()->user()->loans()->findOrFail($id);

        if ($loan->status === 'returned') {
            return response()->json([
                'status' => 'error',
                'message' => 'Book already returned',
            ], 400);
        }

        // Update status dan waktu pengembalian
        $loan->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Book returned successfully',
        ]);
    }

    public function history()
    {
        // Ambil semua pinjaman yang sudah dikembalikan (status 'returned')
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
