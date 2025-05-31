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

    // Ajukan pinjam buku baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Cek apakah user sudah mengajukan atau meminjam buku ini dan belum dikembalikan
        $already = auth()->user()->loans()
            ->where('book_id', $validated['book_id'])
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->first();

        if ($already) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah memiliki pengajuan atau peminjaman aktif untuk buku ini.',
            ], 422);
        }

        // Simpan data peminjaman
        $loan = auth()->user()->loans()->create([
            'book_id' => $validated['book_id'],
            'status' => 'pending',
            'borrowed_at' => null,
            'returned_at' => null,
        ]);

        // Kirim notifikasi ke admin/staff
        Notification::create([
            'type' => 'loan',
            'title' => 'Pengajuan Peminjaman Buku',
            'message' => 'User ' . auth()->user()->name . ' mengajukan peminjaman buku ID: ' . $loan->book_id,
            'is_read' => false,
            'user_id' => auth()->id(),
            'book_id' => $loan->book_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan admin.',
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

        if (!in_array($loan->status, ['approved', 'borrowed'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku belum dipinjam atau sudah dikembalikan.',
            ], 400);
        }

        $loan->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        // Notifikasi untuk admin bahwa user telah mengembalikan buku
        Notification::create([
            'type' => 'return',
            'title' => 'Pengembalian Buku',
            'message' => 'User ' . auth()->user()->name . ' telah mengembalikan buku ID: ' . $loan->book_id,
            'is_read' => false,
            'user_id' => auth()->id(),
            'book_id' => $loan->book_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil dikembalikan.',
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
