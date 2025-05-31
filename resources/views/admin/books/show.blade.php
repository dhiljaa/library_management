@extends('admin.layouts.app')

@section('title', 'Detail Buku')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-10">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8">{{ $book->title }}</h1>

    <div class="flex flex-col md:flex-row md:space-x-10">
        {{-- Gambar Buku --}}
        @if($book->image_url)
            <img 
                src="{{ asset($book->image_url) }}" 
                alt="{{ $book->title }}" 
                class="w-full max-w-xs h-auto rounded-lg shadow-md object-cover mx-auto md:mx-0"
            />
        @else
            <div class="w-full max-w-xs h-64 bg-gray-100 flex items-center justify-center rounded-lg text-gray-400 font-semibold text-sm mx-auto md:mx-0">
                Tidak ada gambar
            </div>
        @endif

        {{-- Detail Buku --}}
        <div class="mt-8 md:mt-0 flex-1 flex flex-col justify-between">
            <div class="space-y-3 text-gray-800 text-base">
                <p><span class="font-semibold text-gray-700">Penulis:</span> {{ $book->author }}</p>
                <p><span class="font-semibold text-gray-700">Penerbit:</span> {{ $book->publisher ?? '-' }}</p>
                <p><span class="font-semibold text-gray-700">Tahun Terbit:</span> {{ $book->published_year }}</p>
                <p><span class="font-semibold text-gray-700">Kategori:</span> {{ $book->category->name ?? '-' }}</p>
                <p><span class="font-semibold text-gray-700">Jumlah Stok:</span> {{ $book->quantity }}</p>
                <p>
                    <span class="font-semibold text-gray-700">Dipinjam:</span> 
                    <span class="inline-block bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $book->borrowed_count }} kali
                    </span>
                </p>
            </div>

            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Deskripsi:</h3>
                <p class="whitespace-pre-line text-gray-700 leading-relaxed">
                    {{ $book->description ?? 'Tidak ada deskripsi.' }}
                </p>
            </div>
        </div>
    </div>

    <div class="mt-10 text-center md:text-left">
        <a 
            href="{{ route('admin.books.index') }}" 
            class="inline-block bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200 text-white font-semibold px-6 py-3 rounded-lg shadow-md"
        >
            &larr; Kembali ke daftar buku
        </a>
    </div>
</div>
@endsection
