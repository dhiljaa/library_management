@extends('admin.layouts.app')
@section('title', 'Kelola Kategori')

@section('content')
@if(session('success'))
    <div class="mb-4 px-4 py-3 rounded border border-green-300 bg-green-100 text-green-700 relative" role="alert">
        {{ session('success') }}
        <button 
            type="button" 
            onclick="this.parentElement.style.display='none';" 
            class="absolute top-1 right-2 text-green-700 hover:text-green-900 focus:outline-none"
            aria-label="Close"
        >
            &times;
        </button>
    </div>
@endif

<h1 class="text-2xl font-semibold mb-6">Kelola Kategori</h1>

<a href="{{ route('admin.categories.create') }}" 
    class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
    Tambah Kategori Baru
</a>

<div class="overflow-x-auto">
    <table class="min-w-full border border-gray-300 table-auto">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 px-4 py-2 text-left">Nama Kategori</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
            <tr class="even:bg-gray-50">
                <td class="border border-gray-300 px-4 py-2">{{ $category->name }}</td>
                <td class="border border-gray-300 px-4 py-2 space-x-2">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" 
                        class="inline-block px-3 py-1 text-sm bg-yellow-400 text-gray-800 rounded hover:bg-yellow-500 transition">
                        Edit
                    </a>

                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline"
                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="inline-block px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center py-4 text-gray-500">Belum ada kategori.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
