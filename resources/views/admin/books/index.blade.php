@extends('admin.layouts.app')

@section('title', 'Kelola Buku')

@section('content')
<div class="container mx-auto py-6 px-4">
    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3 relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" 
                    class="absolute top-2 right-2 text-green-700 hover:text-green-900" 
                    onclick="this.parentElement.style.display='none'">
                &times;
            </button>
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-gray-800">Daftar Buku</h1>
        <a href="{{ route('admin.books.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Buku Baru
        </a>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.books.index') }}" class="mb-8 max-w-4xl grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
    {{-- Input keyword pencarian --}}
    <div class="relative col-span-1">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Cari judul atau penulis..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    {{-- Dropdown kategori --}}
    <div>
        <select name="category_id" class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Tombol submit --}}
    <div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
            Cari
        </button>
    </div>
</form>


    <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 w-24 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300">Penulis</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Terbit</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th scope="col" class="px-4 py-3 w-36 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($books as $book)
                <tr class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-4 py-3">
                        @if($book->image_url)
                            <img 
                                src="{{ asset($book->image_url) }}" 
                                alt="Gambar Buku {{ $book->title }}" 
                                class="w-20 h-20 object-cover rounded-md shadow-sm transition-transform duration-300 hover:scale-105"
                            >
                        @else
                            <span class="text-gray-400 italic text-sm">Tidak ada gambar</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-semibold text-gray-700">{{ $book->title }}</td>
                    <td class="px-4 py-3 border-r border-gray-300 text-gray-600">{{ $book->author }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $book->category->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $book->published_year }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $book->quantity }}</td>
                    <td class="px-4 py-3 text-center flex justify-center gap-x-2">
    <a href="{{ route('admin.books.edit', $book->id) }}" 
       class="inline-block px-3 py-1 text-sm bg-yellow-400 text-yellow-900 rounded hover:bg-yellow-500 shadow" 
       title="Edit Buku" data-tooltip>
        <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
            <path fill-rule="evenodd" d="M2 15.5A1.5 1.5 0 013.5 14h10a.5.5 0 010 1h-10a.5.5 0 01-.5-.5z" clip-rule="evenodd"/>
        </svg>
    </a>

    <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" 
            class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 shadow" 
            title="Hapus Buku" data-tooltip>
            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 6a1 1 0 011-1h6a1 1 0 011 1v8a2 2 0 01-2 2H8a2 2 0 01-2-2V6zM5 5a1 1 0 00-1 1v9a3 3 0 003 3h6a3 3 0 003-3V6a1 1 0 00-1-1H5z" clip-rule="evenodd"/>
            </svg>
        </button>
    </form>
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-500 italic">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                        Belum ada buku yang terdaftar.<br>
                        <a href="{{ route('admin.books.create') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Buku Baru
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $books->withQueryString()->links() }}
    </div>
</div>
@endsection
