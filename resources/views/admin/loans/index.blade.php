@extends('admin.layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="container">
    <h1>Daftar Peminjaman</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Pengguna</th>
            <th>Judul Buku</th>
            <th>Kategori</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($loans as $loan)
            <tr>
                <td>{{ $loan->id }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ $loan->book->category->name ?? '-' }}</td>
                <td>{{ $loan->borrowed_at ? $loan->borrowed_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') : '-' }}</td>
                <td>{{ $loan->returned_at ? $loan->returned_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') : '-' }}</td>
                <td>
                    @switch($loan->status)
                        @case('pending')
                            <span class="badge bg-secondary">Pending</span>
                            @break
                        @case('approved')
                            <span class="badge bg-primary">Disetujui</span>
                            @break
                        @case('borrowed')
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                            @break
                        @case('returned')
                            <span class="badge bg-success">Dikembalikan</span>
                            @break
                        @default
                            <span class="badge bg-dark">-</span>
                    @endswitch
                </td>
                <td>
                    <a href="{{ route('admin.loans.show', $loan->id) }}" class="btn btn-info btn-sm">Detail</a>

                    {{-- Aksi sesuai status --}}
                    @if($loan->status === 'pending')
                        <form action="{{ route('admin.loans.updateStatus', $loan->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Setujui peminjaman ini?')">Setujui</button>
                        </form>
                    @elseif($loan->status === 'approved')
                        <form action="{{ route('admin.loans.updateStatus', $loan->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="borrowed">
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Tandai sebagai dipinjam?')">Pinjamkan</button>
                        </form>
                    @elseif($loan->status === 'borrowed')
                        <form action="{{ route('admin.loans.updateStatus', $loan->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="returned">
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Tandai sebagai dikembalikan?')">Kembalikan</button>
                        </form>
                    @endif

                    <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data peminjaman</td>
            </tr>
        @endforelse
    </tbody>
    </table>

    {{ $loans->links() }}
</div>
@endsection
