@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto mt-12 px-6">
    <h2 class="text-4xl font-extrabold text-gray-900 mb-10">Dashboard Admin</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        {{-- Total Buku --}}
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 flex flex-col items-center">
            <h3 class="text-gray-500 text-sm font-medium mb-2 uppercase tracking-wide">Total Buku</h3>
            <p class="text-5xl font-bold text-indigo-600">{{ number_format($total_books) }}</p>
        </div>

        {{-- Total Pengguna --}}
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 flex flex-col items-center">
            <h3 class="text-gray-500 text-sm font-medium mb-2 uppercase tracking-wide">Total Pengguna</h3>
            <p class="text-5xl font-bold text-green-600">{{ number_format($total_users) }}</p>
        </div>

        {{-- Total Peminjaman --}}
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 flex flex-col items-center">
            <h3 class="text-gray-500 text-sm font-medium mb-2 uppercase tracking-wide">Total Peminjaman</h3>
            <p class="text-5xl font-bold text-yellow-500">{{ number_format($total_loans) }}</p>
        </div>

        {{-- Peminjaman Aktif --}}
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 flex flex-col items-center">
            <h3 class="text-gray-500 text-sm font-medium mb-2 uppercase tracking-wide">Peminjaman Aktif</h3>
            <p class="text-5xl font-bold text-red-600">{{ number_format($active_loans) }}</p>
        </div>

        {{-- Peminjam Minggu Ini --}}
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 p-6 flex flex-col items-center sm:col-span-2 md:col-span-1 lg:col-span-1">
            <h3 class="text-gray-500 text-sm font-medium mb-2 uppercase tracking-wide">Peminjam Minggu Ini</h3>
            <p class="text-5xl font-bold text-indigo-700">{{ number_format($weekly_borrowers) }}</p>
        </div>
    </div>

    {{-- Buku Terpopuler --}}
    <div class="mt-16">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">📚 Buku Terpopuler Minggu Ini</h3>

        @if($popular_books->isEmpty())
            <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
                Tidak ada data buku populer minggu ini.
            </div>
        @else
            <div class="bg-white rounded-xl shadow p-6">
                <ul class="divide-y divide-gray-200">
                    @foreach($popular_books as $book)
                        <li class="py-4 flex items-center space-x-4">
                            {{-- Gambar Buku --}}
                            <div class="flex-shrink-0 w-16 h-20 overflow-hidden rounded-md border border-gray-200 bg-gray-100">
                                @if(!empty($book->image_url))
                                    <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                        No Image
                                    </div>
                                @endif
                            </div>

                            {{-- Judul dan Penulis --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('admin.books.show', $book->id) }}" class="text-lg font-semibold text-indigo-600 hover:underline truncate" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </a>
                                <p class="text-sm text-gray-600 truncate">oleh {{ $book->author }}</p>
                            </div>

                            {{-- Jumlah Peminjaman --}}
                            <span class="text-sm font-medium bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full whitespace-nowrap">
                                {{ $book->loans_count }}x dipinjam
                            </span>
                        </li>
                    @endforeach
                </ul>

                {{-- Pagination Links --}}
                <div class="mt-6">
                    {{ $popular_books->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Buku Top Rating --}}
    <div class="mt-16">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">⭐ Buku Top Rating</h3>

        @if($top_rated_books->isEmpty())
            <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
                Tidak ada data buku dengan rating.
            </div>
        @else
            <div class="bg-white rounded-xl shadow p-6">
                <ul class="divide-y divide-gray-200">
                    @foreach($top_rated_books as $book)
                        <li class="py-4 flex items-center space-x-4">
                            {{-- Gambar Buku --}}
                            <div class="flex-shrink-0 w-16 h-20 overflow-hidden rounded-md border border-gray-200 bg-gray-100">
                                @if(!empty($book->image_url))
                                    <img src="{{ asset($book->image_url) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                        No Image
                                    </div>
                                @endif
                            </div>

                            {{-- Judul dan Penulis --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('admin.books.show', $book->id) }}" class="text-lg font-semibold text-yellow-600 hover:underline truncate" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </a>
                                <p class="text-sm text-gray-600 truncate">oleh {{ $book->author }}</p>

                                {{-- Rating Bintang --}}
                                <div class="flex items-center mt-1">
                                    @php
                                        $rating = round($book->reviews_avg_rating, 1);
                                        $fullStars = floor($rating);
                                        $halfStar = ($rating - $fullStars) >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                    @endphp

                                    {{-- Full Stars --}}
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.92-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.785.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.036 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
                                        </svg>
                                    @endfor

                                    {{-- Half Star --}}
                                    @if ($halfStar)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <defs>
                                                <linearGradient id="half-grad" x1="0%" y1="0%" x2="100%" y2="0%">
                                                    <stop offset="50%" stop-color="currentColor" />
                                                    <stop offset="50%" stop-color="transparent" />
                                                </linearGradient>
                                            </defs>
                                            <path fill="url(#half-grad)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.92-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.785.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.036 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
                                        </svg>
                                    @endif

                                    {{-- Empty Stars --}}
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.92-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.785.57-1.838-.197-1.54-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.036 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>
                                        </svg>
                                    @endfor

                                    <span class="ml-2 text-sm font-medium text-gray-600">{{ number_format($rating, 1) }}/5</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $top_rated_books->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
