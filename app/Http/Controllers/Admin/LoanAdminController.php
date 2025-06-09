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
    // Tampilkan daftar peminjaman dengan filter, sorting, dan pagination
    public function index(Request $request)
    {
        $query = Loan::with(['user', 'book.category']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan keyword pencarian gabungan user dan judul buku
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

        // Filter bulan dan tahun berdasarkan borrowed_at
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('borrowed_at', $request->bulan)
                  ->whereYear('borrowed_at', $request->tahun);
        }

        // Sorting dinamis
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['id', 'created_at', 'borrowed_at', 'returned_at', 'status'];
        $allowedSortDirections = ['asc', 'desc'];

        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }
        if (!in_array($sortDirection, $allowedSortDirections)) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        $perPage = (int) $request->get('per_page', 15);
        $perPage = $perPage > 0 && $perPage <= 100 ? $perPage : 15;

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

    public function search(Request $request)
    {
        return $this->index($request);
    }

    // Export data peminjaman ke PDF dengan filter dan judul bulan tahun dinamis
    public function exportPDF(Request $request)
    {
        $query = Loan::with(['user', 'book.category']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter search gabungan user dan buku
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

        // Sorting
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['id', 'created_at', 'borrowed_at', 'returned_at', 'status'];
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

    public function show($id)
    {
        $loan = Loan::with(['user', 'book'])->findOrFail($id);
        return view('admin.loans.show', compact('loan'));
    }

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
                'type' => 'approved',
                'title' => 'Peminjaman Disetujui',
                'message' => 'Admin menyetujui peminjaman buku "' . $loan->book->title . '" oleh ' . $loan->user->name . '.',
                'is_read' => false,
                'user_id' => $loan->user->id,
                'book_id' => $loan->book->id,
            ]);
        }

        if ($newStatus === 'borrowed') {
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
        }

        if ($newStatus === 'returned') {
            $loan->returned_at = $request->returned_at ?? now();
            $loan->status = 'returned';

            Notification::create([
                'type' => 'returned',
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

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
