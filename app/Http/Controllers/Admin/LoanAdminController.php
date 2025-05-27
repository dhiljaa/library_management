<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanAdminController extends Controller
{
    /**
     * Tampilkan daftar peminjaman dengan relasi user dan book.
     * Data diurutkan berdasarkan tanggal pinjam terbaru.
     */
    public function index()
    {
        $loans = Loan::with(['user', 'book'])
            ->orderByDesc('borrowed_at')
            ->paginate(15);

        return view('admin.loans.index', compact('loans'));
    }

    /**
     * Tampilkan detail peminjaman berdasarkan ID.
     */
    public function show($id)
    {
        $loan = Loan::with(['user', 'book'])->findOrFail($id);
        return view('admin.loans.show', compact('loan'));
    }

    /**
     * Update status peminjaman (misal: borrowed, returned).
     * Mendukung update untuk kembalikan dan pinjam ulang.
     */
    public function updateStatus(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:borrowed,returned',
            'returned_at' => 'nullable|date',
            'borrowed_at' => 'nullable|date',
        ]);

        $newStatus = $request->status;

        if ($newStatus === 'returned') {
            // Jika status returned, set returned_at jika belum ada
            if (!$loan->returned_at) {
                $loan->returned_at = $request->returned_at ?? now();
            }
        } elseif ($newStatus === 'borrowed') {
            // Jika status pinjam ulang, reset returned_at dan update borrowed_at
            $loan->returned_at = null;
            $loan->borrowed_at = $request->borrowed_at ?? now();
        }

        $loan->status = $newStatus;

        $loan->save();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Status peminjaman berhasil diperbarui');
    }

    /**
     * Hapus data peminjaman.
     */
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Data peminjaman berhasil dihapus');
    }
}
