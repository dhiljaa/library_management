@extends('admin.layouts.app')

@section('title', 'Data Peminjaman Buku')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-semibold mb-6">Data Peminjaman Buku</h1>

    <!-- Form Filter -->
    <form action="{{ route('admin.loans.index') }}" method="GET" class="mb-6 bg-white p-4 rounded shadow-md">
        <div class="grid grid-cols-12 gap-4 items-end">
            <!-- Status -->
            <div class="col-span-2">
                <label for="status" class="block mb-1 font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" {{ empty(request('status')) ? 'selected' : '' }}>Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                </select>
            </div>

            <!-- Search -->
            <div class="col-span-3">
                <label for="search" class="block mb-1 font-medium text-gray-700">Cari (User / Judul Buku)</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ketik nama user atau judul buku...">
            </div>

            <!-- Bulan -->
            <div class="col-span-2">
                <label for="bulan" class="block mb-1 font-medium text-gray-700">Bulan</label>
                <select name="bulan" id="bulan" class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua</option>
                    @foreach(range(1, 12) as $bulan)
                        <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->isoFormat('MMMM') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div class="col-span-2">
                <label for="tahun" class="block mb-1 font-medium text-gray-700">Tahun</label>
                <select name="tahun" id="tahun" class="w-full border-gray-300 rounded shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua</option>
                    @php
                        $yearNow = date('Y');
                        $startYear = $yearNow - 5;
                    @endphp
                    @for($year = $yearNow; $year >= $startYear; $year--)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <!-- Submit -->
            <div class="col-span-1">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded shadow">Filter</button>
            </div>

            <!-- Reset Filter -->
            <div class="col-span-1">
                <a href="{{ route('admin.loans.index') }}" class="w-full block text-center bg-gray-300 hover:bg-gray-400 font-semibold py-2 rounded shadow">Reset</a>
            </div>
        </div>
    </form>

    <!-- Export PDF -->
    <div class="mb-4">
        <a href="{{ route('admin.loans.export.pdf', request()->all()) }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow">
            Export PDF
        </a>
    </div>

    <!-- Tabel Peminjaman -->
    <div class="overflow-x-auto bg-white rounded shadow-md">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @php
                        // Helper untuk toggle arah sort
                        function sortDirectionToggle($currentDir) {
                            return $currentDir === 'asc' ? 'desc' : 'asc';
                        }
                        $currentSortField = request('sort_field', 'created_at');
                        $currentSortDirection = request('sort_direction', 'desc');
                        $queryParams = request()->except(['page', 'sort_field', 'sort_direction']);
                    @endphp

                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('admin.loans.index', array_merge($queryParams, ['sort_field' => 'id', 'sort_direction' => $currentSortField === 'id' ? sortDirectionToggle($currentSortDirection) : 'asc'])) }}">
                            ID {!! $currentSortField === 'id' ? ($currentSortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}
                        </a>
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('admin.loans.index', array_merge($queryParams, ['sort_field' => 'borrowed_at', 'sort_direction' => $currentSortField === 'borrowed_at' ? sortDirectionToggle($currentSortDirection) : 'asc'])) }}">
                            Tanggal Pinjam {!! $currentSortField === 'borrowed_at' ? ($currentSortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}
                        </a>
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('admin.loans.index', array_merge($queryParams, ['sort_field' => 'returned_at', 'sort_direction' => $currentSortField === 'returned_at' ? sortDirectionToggle($currentSortDirection) : 'asc'])) }}">
                            Tanggal Kembali {!! $currentSortField === 'returned_at' ? ($currentSortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}
                        </a>
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('admin.loans.index', array_merge($queryParams, ['sort_field' => 'status', 'sort_direction' => $currentSortField === 'status' ? sortDirectionToggle($currentSortDirection) : 'asc'])) }}">
                            Status {!! $currentSortField === 'status' ? ($currentSortDirection === 'asc' ? '&#9650;' : '&#9660;') : '' !!}
                        </a>
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($loans as $loan)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $loan->id }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $loan->user->name ?? '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $loan->book->title ?? '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $loan->borrowed_at ? \Carbon\Carbon::parse($loan->borrowed_at)->format('d-m-Y') : '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $loan->returned_at ? \Carbon\Carbon::parse($loan->returned_at)->format('d-m-Y') : '-' }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            @php
                                $statusLabels = [
                                    'pending' => 'bg-yellow-400 text-yellow-900',
                                    'approved' => 'bg-blue-400 text-blue-900',
                                    'borrowed' => 'bg-indigo-400 text-indigo-900',
                                    'returned' => 'bg-green-400 text-green-900',
                                ];
                                $badgeClass = $statusLabels[$loan->status] ?? 'bg-gray-400 text-gray-900';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap space-x-1">
                            <a href="{{ route('admin.loans.show', $loan->id) }}" class="inline-block px-2 py-1 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded shadow">Detail</a>

                            @if($loan->status !== 'returned')
                                <!-- Tombol Kembalikan -->
                                <form action="{{ route('admin.loans.updateStatus', $loan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah anda yakin ingin mengubah status menjadi Returned?')">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="returned">
                                    <button type="submit" class="px-2 py-1 text-sm bg-green-600 hover:bg-green-700 text-white rounded shadow">Kembalikan</button>
                                </form>
                            @endif

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded shadow">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">Data peminjaman tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4 text-gray-600">
        <div>
            Menampilkan {{ $loans->firstItem() ?? 0 }} sampai {{ $loans->lastItem() ?? 0 }} dari total {{ $loans->total() }} data
        </div>
        <div>
            {{ $loans->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
