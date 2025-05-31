@extends('admin.layouts.app')

@section('title', 'Detail Peminjaman #' . $loan->id)

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-gray-50 min-h-screen rounded-lg shadow-lg">
    <h1 class="text-4xl font-extrabold mb-10 text-gray-900 border-b border-gray-300 pb-4">Detail Peminjaman #{{ $loan->id }}</h1>

    {{-- Informasi Peminjaman --}}
    <section class="bg-white shadow-md rounded-lg p-6 mb-8 hover:shadow-lg transition-shadow duration-300">
        <h2 class="text-2xl font-semibold mb-5 text-gray-800 border-b border-gray-200 pb-2">Informasi Peminjaman</h2>
        <div class="space-y-3 text-gray-700 text-lg">
            <p><span class="font-semibold">Status:</span> 
                @if($loan->status === 'borrowed')
                    <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-semibold">Dipinjam</span>
                @elseif($loan->status === 'returned')
                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">Dikembalikan</span>
                @else
                    <span class="inline-block bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-semibold capitalize">{{ $loan->status }}</span>
                @endif
            </p>
            <p><span class="font-semibold">Tanggal Pinjam:</span> {{ $loan->borrowed_at->format('d M Y H:i') }}</p>
            <p><span class="font-semibold">Tanggal Kembali:</span> 
                {{ $loan->returned_at ? $loan->returned_at->format('d M Y H:i') : '-' }}
            </p>
        </div>
    </section>

    {{-- Informasi Pengguna --}}
    <section class="bg-white shadow-md rounded-lg p-6 mb-8 hover:shadow-lg transition-shadow duration-300">
        <h2 class="text-2xl font-semibold mb-5 text-gray-800 border-b border-gray-200 pb-2">Informasi Pengguna</h2>
        <div class="space-y-3 text-gray-700 text-lg">
            <p><span class="font-semibold">Nama:</span> {{ $loan->user->name }}</p>
            <p><span class="font-semibold">Email:</span> {{ $loan->user->email }}</p>
        </div>
    </section>

    {{-- Informasi Buku --}}
    <section class="bg-white shadow-md rounded-lg p-6 mb-8 hover:shadow-lg transition-shadow duration-300">
        <h2 class="text-2xl font-semibold mb-5 text-gray-800 border-b border-gray-200 pb-2">Informasi Buku</h2>
        <div class="space-y-3 text-gray-700 text-lg">
            <p><span class="font-semibold">Judul:</span> {{ $loan->book->title }}</p>
            <p><span class="font-semibold">Pengarang:</span> {{ $loan->book->author }}</p>
            <p><span class="font-semibold">Kategori:</span> {{ $loan->book->category->name ?? '-' }}</p>
        </div>
    </section>

    {{-- Form Update Status --}}
    <section class="bg-white shadow-md rounded-lg p-6 mb-12 hover:shadow-lg transition-shadow duration-300">
        <h2 class="text-2xl font-semibold mb-5 text-gray-800 border-b border-gray-200 pb-2">Update Status Peminjaman</h2>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-md border border-green-300 flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.loans.updateStatus', $loan->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="status" class="block mb-2 font-semibold text-gray-700">Status</label>
                <select name="status" id="status" 
                    class="w-full rounded-md border border-gray-300 p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 @error('status') border-red-500 @enderror">
                    <option value="borrowed" {{ $loan->status === 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="returned" {{ $loan->status === 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
                @error('status')
                    <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="returned_at" class="block mb-2 font-semibold text-gray-700">Tanggal Kembali (jika dikembalikan)</label>
                <input 
                    type="date" 
                    name="returned_at" 
                    id="returned_at" 
                    value="{{ old('returned_at', $loan->returned_at ? $loan->returned_at->format('Y-m-d') : '') }}"
                    class="w-full rounded-md border border-gray-300 p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 @error('returned_at') border-red-500 @enderror"
                    {{ $loan->status === 'returned' ? '' : 'disabled' }}>
                @error('returned_at')
                    <p class="mt-1 text-red-600 text-sm font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 rounded-md shadow-md transition duration-150">
                Update Status
            </button>
        </form>
    </section>

    <div class="mt-8 text-center">
        <a href="{{ route('admin.loans.index') }}" 
           class="inline-block text-blue-600 hover:underline font-semibold">&larr; Kembali ke Daftar Peminjaman</a>
    </div>
</div>

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
        toggleReturnedAt();
    });
</script>
@endpush

@endsection
