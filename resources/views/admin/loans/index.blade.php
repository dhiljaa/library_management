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
                    <td>{{ $loan->borrowed_at ? $loan->borrowed_at->format('d-m-Y H:i') : '-' }}</td>
                    <td>{{ $loan->returned_at ? $loan->returned_at->format('d-m-Y H:i') : '-' }}</td>
                    <td>
                        @if($loan->status === 'borrowed')
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                        @else
                            <span class="badge bg-success">Dikembalikan</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.loans.show', $loan->id) }}" class="btn btn-info btn-sm">Detail</a>
                        
                        <!-- Form update status -->
                        <form action="{{ route('admin.loans.updateStatus', $loan->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')

                            @if($loan->status === 'borrowed')
                                <input type="hidden" name="status" value="returned">
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Tandai sebagai dikembalikan?')">Kembalikan</button>
                            @else
                                <input type="hidden" name="status" value="borrowed">
                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Tandai sebagai dipinjam?')">Pinjamkan</button>
                            @endif
                        </form>

                        <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data peminjaman</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $loans->links() }}
</div>
@endsection
