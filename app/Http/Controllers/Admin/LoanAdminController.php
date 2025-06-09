<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Notification;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class LoanAdminController extends Controller
{
    // Menampilkan daftar peminjaman dengan filter, sorting, dan pagination
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'book.category']);

        // Filter berdasarkan status peminjaman
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter pencarian gabungan berdasarkan nama user atau judul buku
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('book', function ($q3) use ($search) {
                    $q3->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filter berdasarkan bulan dan tahun dari tanggal borrowed_at
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('borrowed_at', $request->bulan)
                  ->whereYear('borrowed_at', $request->tahun);
        }

        // Sorting dinamis dengan validasi field dan direction
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['id', 'created_at', 'borrowed_at', 'returned_at', 'status', 'penalty'];
        $allowedSortDirections = ['asc', 'desc'];

        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }
        if (!in_array($sortDirection, $allowedSortDirections)) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        // Pagination, batasi per halaman max 100
        $perPage = (int) $request->get('per_page', 15);
        $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 15;

        $loans = $query->paginate($perPage)->withQueryString();

        return view('admin.loans.index', compact('loans'))
            ->with([
                'filterStatus' => $request->status,
                'searchKeyword' => $request->search,
                'filterBulan' => $request->bulan,
                'filterTahun' => $request->tahun,
                'sortField' => $sortField,
                'sortDirection' => $sortDirection,
                'perPage' => $perPage,
            ]);
    }

    // Fungsi pencarian alias memanggil index dengan request yang sama
    public function search(Request $request)
    {
        return $this->index($request);
    }

    // Export daftar peminjaman ke PDF dengan filter dan label bulan tahun dinamis
    public function exportPDF(Request $request)
    {
        $query = Loan::with(['user', 'book.category']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter pencarian gabungan user dan buku
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('book', function ($q3) use ($search) {
                    $q3->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filter bulan dan tahun pada borrowed_at
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('borrowed_at', $request->bulan)
                  ->whereYear('borrowed_at', $request->tahun);
        }

        // Sorting dengan validasi
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['id', 'created_at', 'borrowed_at', 'returned_at', 'status', 'penalty'];
        $allowedSortDirections = ['asc', 'desc'];

        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }
        if (!in_array($sortDirection, $allowedSortDirections)) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        $loans = $query->get();

        // Tentukan label bulan-tahun untuk judul laporan
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $bulanTahun = Carbon::createFromDate($request->tahun, $request->bulan, 1)
                ->locale('id')->isoFormat('MMMM YYYY');
        } else {
            $firstBorrowedAt = $loans->whereNotNull('borrowed_at')->sortBy('borrowed_at')->first();
            $bulanTahun = $firstBorrowedAt
                ? Carbon::parse($firstBorrowedAt->borrowed_at)->locale('id')->isoFormat('MMMM YYYY')
                : Carbon::now()->locale('id')->isoFormat('MMMM YYYY');
        }

        $pdf = PDF::loadView('admin.loans.export_pdf', compact('loans', 'bulanTahun'));

        return $pdf->download('loans_' . now()->format('Ymd_His') . '.pdf');
    }

    // Tampilkan detail peminjaman
    public function show($id)
    {
        $loan = Loan::with(['user', 'book'])->findOrFail($id);
        return view('admin.loans.show', compact('loan'));
    }

    // Update status peminjaman dan buat notifikasi sesuai status baru
    public function updateStatus(Request $request, $id)
    {
        $loan = Loan::with('user', 'book')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,borrowed,returned,overdue,rejected',
            'borrowed_at' => 'nullable|date',
            'returned_at' => 'nullable|date',
            'is_penalty_paid' => 'nullable|boolean',
        ]);

        $newStatus = $request->status;
        $oldStatus = $loan->status;

        if ($newStatus === 'approved' && $oldStatus === 'pending') {
            $loan->borrowed_at = $request->borrowed_at ?? now();
            $loan->status = 'approved';

            Notification::create([
                'type' => 'approved',
                'title' => 'Peminjaman Disetujui',
                'message' => 'Admin menyetujui peminjaman buku "' . $loan->book->title . '" oleh ' . $loan->user->name . '.',
                'is_read' => false,
                'user_id' => $loan->user->id,
                'book_id' => $loan->book->id,
            ]);
        } elseif ($newStatus === 'rejected' && $oldStatus === 'pending') {
            $loan->status = 'rejected';

            Notification::create([
                'type' => 'rejected',
                'title' => 'Peminjaman Ditolak',
                'message' => 'Admin menolak peminjaman buku "' . $loan->book->title . '" oleh ' . $loan->user->name . '.',
                'is_read' => false,
                'user_id' => $loan->user->id,
                'book_id' => $loan->book->id,
            ]);
        } elseif ($newStatus === 'borrowed') {
            $loan->borrowed_at = $request->borrowed_at ?? now();
            $loan->returned_at = null;
            $loan->status = 'borrowed';

            Notification::create([
                'type' => 'borrowed',
                'title' => 'Peminjaman Dimulai (Admin)',
                'message' => 'Admin menandai buku "' . $loan->book->title . '" telah dipinjam oleh ' . $loan->user->name . '.',
                'is_read' => false,
                'user_id' => $loan->user->id,
                'book_id' => $loan->book->id,
            ]);
        } elseif ($newStatus === 'returned') {
            $loan->returned_at = $request->returned_at ?? now();
            $loan->status = 'returned';

            // Hitung dan update denda otomatis (langsung simpan)
            $loan->updatePenalty();

            Notification::create([
                'type' => 'returned',
                'title' => 'Pengembalian Buku (Admin)',
                'message' => 'Admin menandai peminjaman buku "' . $loan->book->title . '" oleh ' . $loan->user->name . ' sebagai telah dikembalikan.',
                'is_read' => false,
                'user_id' => $loan->user->id,
                'book_id' => $loan->book->id,
            ]);
        } elseif ($newStatus === 'overdue') {
            $loan->status = 'overdue';

            Notification::create([
                'type' => 'overdue',
                'title' => 'Peminjaman Lewat Batas Waktu',
                'message' => 'Peminjaman buku "' . $loan->book->title . '" oleh ' . $loan->user->name . ' telah melewati batas waktu pengembalian.',
                'is_read' => false,
                'user_id' => $loan->user->id,
                'book_id' => $loan->book->id,
            ]);
        } elseif ($newStatus === 'pending') {
            // Jika rollback ke pending
            $loan->status = 'pending';
        }

        // Update status pembayaran denda jika ada
        if ($request->has('is_penalty_paid')) {
            $loan->is_penalty_paid = $request->is_penalty_paid;
        }

        $loan->save();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    // Approve peminjaman khusus method
    public function approve($id)
    {
        $loan = Loan::with('user', 'book')->findOrFail($id);

        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Peminjaman ini tidak dalam status pending.');
        }

        $loan->status = 'approved';
        $loan->borrowed_at = now();
        $loan->save();

        Notification::create([
            'type' => 'approved',
            'title' => 'Peminjaman Disetujui',
            'message' => 'Admin menyetujui peminjaman buku "' . $loan->book->title . '" oleh ' . $loan->user->name . '.',
            'is_read' => false,
            'user_id' => $loan->user->id,
            'book_id' => $loan->book->id,
        ]);

        return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil disetujui.');
    }

    // Reject peminjaman khusus method
    public function reject($id)
    {
        $loan = Loan::with('user', 'book')->findOrFail($id);

        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Peminjaman ini tidak dalam status pending.');
        }

        $loan->status = 'rejected';
        $loan->save();

        Notification::create([
            'type' => 'rejected',
            'title' => 'Peminjaman Ditolak',
            'message' => 'Admin menolak peminjaman buku "' . $loan->book->title . '" oleh ' . $loan->user->name . '.',
            'is_read' => false,
            'user_id' => $loan->user->id,
            'book_id' => $loan->book->id,
        ]);

        return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil ditolak.');
    }

    // Bayar denda (update is_penalty_paid)
    public function payPenalty(Request $request, $id)
    {
        $loan = Loan::with('user', 'book')->findOrFail($id);

        if ($loan->penalty <= 0) {
            return redirect()->back()->with('error', 'Tidak ada denda yang harus dibayar.');
        }

        if ($loan->is_penalty_paid) {
            return redirect()->back()->with('error', 'Denda sudah dibayar.');
        }

        $loan->is_penalty_paid = true;
        $loan->save();

        Notification::create([
            'type' => 'penalty_paid',
            'title' => 'Denda Telah Dibayar',
            'message' => 'Pengguna telah membayar denda untuk buku "' . $loan->book->title . '".',
            'is_read' => false,
            'user_id' => $loan->user->id,
            'book_id' => $loan->book->id,
        ]);

        return redirect()->back()->with('success', 'Pembayaran denda berhasil.');
    }

    // Hapus peminjaman
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }
}
