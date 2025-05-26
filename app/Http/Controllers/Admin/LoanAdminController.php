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
     */
    public function updateStatus(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:borrowed,returned',
            'returned_at' => 'nullable|date',
        ]);

        $loan->status = $request->status;

        // Jika status returned, isi returned_at jika belum ada
        if ($loan->status === 'returned' && !$loan->returned_at) {
            $loan->returned_at = $request->returned_at ?? now();
        }

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
