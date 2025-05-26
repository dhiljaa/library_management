@extends('admin.layouts.app')

@section('title', 'Detail Peminjaman #' . $loan->id)

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Detail Peminjaman #{{ $loan->id }}</h1>

    {{-- Informasi Peminjaman --}}
    <div class="bg-white shadow rounded p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Informasi Peminjaman</h2>
        <p><strong>Status:</strong> 
            @if($loan->status === 'borrowed')
                <span class="text-yellow-600 font-semibold">Dipinjam</span>
            @elseif($loan->status === 'returned')
                <span class="text-green-600 font-semibold">Dikembalikan</span>
            @endif
        </p>
        <p><strong>Tanggal Pinjam:</strong> {{ $loan->borrowed_at->format('d M Y H:i') }}</p>
        <p><strong>Tanggal Kembali:</strong> 
            {{ $loan->returned_at ? $loan->returned_at->format('d M Y H:i') : '-' }}
        </p>
    </div>

    {{-- Informasi User --}}
    <div class="bg-white shadow rounded p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Informasi Pengguna</h2>
        <p><strong>Nama:</strong> {{ $loan->user->name }}</p>
        <p><strong>Email:</strong> {{ $loan->user->email }}</p>
    </div>

    {{-- Informasi Buku --}}
    <div class="bg-white shadow rounded p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Informasi Buku</h2>
        <p><strong>Judul:</strong> {{ $loan->book->title }}</p>
        <p><strong>Pengarang:</strong> {{ $loan->book->author }}</p>
        <p><strong>Kategori:</strong> {{ $loan->book->category->name ?? '-' }}</p>
    </div>

    {{-- Form Update Status --}}
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-xl font-semibold mb-4">Update Status Peminjaman</h2>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.loans.updateStatus', $loan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="status" class="block mb-2 font-semibold">Status</label>
            <select name="status" id="status" class="border rounded p-2 w-full mb-4 @error('status') border-red-500 @enderror">
                <option value="borrowed" {{ $loan->status === 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                <option value="returned" {{ $loan->status === 'returned' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
            @error('status')
                <p class="text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <label for="returned_at" class="block mb-2 font-semibold">Tanggal Kembali (jika dikembalikan)</label>
            <input 
                type="date" 
                name="returned_at" 
                id="returned_at" 
                value="{{ old('returned_at', $loan->returned_at ? $loan->returned_at->format('Y-m-d') : '') }}"
                class="border rounded p-2 w-full @error('returned_at') border-red-500 @enderror"
                {{ $loan->status === 'returned' ? '' : 'disabled' }}>
            @error('returned_at')
                <p class="text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700">
                Update Status
            </button>
        </form>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.loans.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Daftar Peminjaman</a>
    </div>
</div>

{{-- Script untuk toggle field returned_at --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('status');
        const returnedAtInput = document.getElementById('returned_at');

        function toggleReturnedAt() {
            if (statusSelect.value === 'returned') {
                returnedAtInput.disabled = false;
            } else {
                returnedAtInput.disabled = true;
                returnedAtInput.value = '';
            }
        }

        statusSelect.addEventListener('change', toggleReturnedAt);
        toggleReturnedAt(); // Inisialisasi awal
    });
</script>
@endpush

@endsection
