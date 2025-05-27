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
</div>
@endsection
