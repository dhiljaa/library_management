<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Notification;
use Illuminate\Http\Request;

class LoanAdminController extends Controller
{
    // Tampilkan daftar semua peminjaman
    public function index()
    {
        $loans = Loan::with(['user', 'book'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.loans.index', compact('loans'));
    }

    // Tampilkan detail peminjaman
    public function show($id)
    {
        $loan = Loan::with(['user', 'book'])->findOrFail($id);
        return view('admin.loans.show', compact('loan'));
    }

    // Update status peminjaman
    public function updateStatus(Request $request, $id)
    {
        $loan = Loan::with('user', 'book')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,borrowed,returned',
            'borrowed_at' => 'nullable|date',
            'returned_at' => 'nullable|date',
        ]);

        $newStatus = $request->status;
        $oldStatus = $loan->status;

       if ($newStatus === 'approved' && $oldStatus === 'pending') {
    $loan->borrowed_at = $request->borrowed_at ?? now();
    $loan->status = 'approved';

    Notification::create([
        'type' => 'approved',  // tambahkan ini
        'title' => 'Peminjaman Disetujui',
        'message' => 'Admin menyetujui peminjaman buku "' . $loan->book->title . '" oleh ' . $loan->user->name . '.',
        'is_read' => false,
        'user_id' => $loan->user->id,   // opsional, tapi biasanya penting
        'book_id' => $loan->book->id,   // opsional, tapi biasanya penting
    ]);
}

if ($newStatus === 'borrowed') {
    $loan->borrowed_at = $request->borrowed_at ?? now();
    $loan->returned_at = null;
    $loan->status = 'borrowed';

    Notification::create([
        'type' => 'borrowed',  // tambahkan ini
        'title' => 'Peminjaman Dimulai (Admin)',
        'message' => 'Admin menandai buku "' . $loan->book->title . '" telah dipinjam oleh ' . $loan->user->name . '.',
        'is_read' => false,
        'user_id' => $loan->user->id,
        'book_id' => $loan->book->id,
    ]);
}

if ($newStatus === 'returned') {
    $loan->returned_at = $request->returned_at ?? now();
    $loan->status = 'returned';

    Notification::create([
        'type' => 'returned',  // tambahkan ini
        'title' => 'Pengembalian Buku (Admin)',
        'message' => 'Admin menandai peminjaman buku "' . $loan->book->title . '" oleh ' . $loan->user->name . ' sebagai telah dikembalikan.',
        'is_read' => false,
        'user_id' => $loan->user->id,
        'book_id' => $loan->book->id,
    ]);
}

        $loan->save();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    // Hapus data peminjaman
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
