<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Notification;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Tampilkan semua peminjaman user (semua status), sekaligus update status overdue dan penalty.
     */
    public function index()
    {
        $user = auth()->user();

        $loans = $user->loans()->with('book')->get();

        // Update status overdue dan penalty tiap pinjaman sebelum ditampilkan
        foreach ($loans as $loan) {
            $loan->checkOverdue();
            $loan->updatePenalty();
        }

        return response()->json([
            'status' => 'success',
            'data' => $loans->map([$this, 'formatLoan']),
        ]);
    }

    /**
     * Ajukan peminjaman buku baru (status default: pending).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user = auth()->user();

        // Cek apakah sudah ada peminjaman aktif untuk buku ini
        $existing = $user->loans()
            ->where('book_id', $validated['book_id'])
            ->whereIn('status', ['pending', 'approved', 'borrowed'])
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah memiliki pengajuan atau peminjaman aktif untuk buku ini.',
            ], 422);
        }

        // Buat peminjaman baru dengan status pending
        $loan = $user->loans()->create([
            'book_id'         => $validated['book_id'],
            'status'          => 'pending',
            'borrowed_at'     => null,
            'returned_at'     => null,
            'penalty'         => 0,
            'is_penalty_paid' => false,
        ]);

        // Kirim notifikasi ke admin
        Notification::create([
            'type'     => 'loan',
            'title'    => 'Pengajuan Peminjaman Buku',
            'message'  => "User {$user->name} mengajukan peminjaman buku ID: {$loan->book_id}",
            'is_read'  => false,
            'user_id'  => $user->id,
            'book_id'  => $loan->book_id,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan admin.',
            'data'    => $this->formatLoan($loan),
        ], 201);
    }

    /**
     * User mengembalikan buku, hitung denda dan update status.
     */
    public function return($id)
    {
        $user = auth()->user();
        $loan = $user->loans()->findOrFail($id);

        // Pastikan status peminjaman valid untuk pengembalian
        if (!in_array($loan->status, ['approved', 'borrowed', 'overdue'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku belum dipinjam atau sudah dikembalikan.',
            ], 400);
        }

        $loan->returned_at = now();
        $loan->status = 'returned';

        // Hitung dan update denda
        $loan->updatePenalty();

        // Simpan perubahan
        $loan->save();

        // Kirim notifikasi ke admin
        Notification::create([
            'type'     => 'return',
            'title'    => 'Pengembalian Buku',
            'message'  => "User {$user->name} telah mengembalikan buku ID: {$loan->book_id}",
            'is_read'  => false,
            'user_id'  => $user->id,
            'book_id'  => $loan->book_id,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Buku berhasil dikembalikan.',
            'data'    => $this->formatLoan($loan),
        ]);
    }

    /**
     * Tampilkan riwayat peminjaman yang sudah dikembalikan, sekaligus update penalty.
     */
    public function history()
    {
        $user = auth()->user();

        $loans = $user->loans()
            ->where('status', 'returned')
            ->with('book')
            ->get();

        // Update penalty jika diperlukan (misal ada perubahan)
        foreach ($loans as $loan) {
            $loan->updatePenalty();
        }

        return response()->json([
            'status' => 'success',
            'data' => $loans->map([$this, 'formatLoan']),
        ]);
    }

    /**
     * Format data peminjaman standar untuk output JSON.
     */
    private function formatLoan(Loan $loan)
    {
        return [
            'id'              => $loan->id,
            'book_id'         => $loan->book_id,
            'user_id'         => $loan->user_id,
            'borrowed_at'     => $loan->borrowed_at,
            'returned_at'     => $loan->returned_at,
            'status'          => $loan->status,
            'penalty'         => $loan->penalty,
            'is_penalty_paid' => $loan->is_penalty_paid,
            'book'            => $loan->book,
        ];
    }
}
