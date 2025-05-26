@extends('admin.layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Tambah Buku Baru</h1>

<form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div>
        <label for="title" class="block mb-1 font-medium text-gray-700">Judul</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required
            class="form-control block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
            @error('title') is-invalid @enderror">
        @error('title')
            <div class="invalid-feedback text-red-600 mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="author" class="block mb-1 font-medium text-gray-700">Penulis</label>
        <input type="text" id="author" name="author" value="{{ old('author') }}" required
            class="form-control block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
            @error('author') is-invalid @enderror">
        @error('author')
            <div class="invalid-feedback text-red-600 mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kategori dropdown --}}
    <div>
        <label for="category_id" class="block mb-1 font-medium text-gray-700">Kategori</label>
        <select id="category_id" name="category_id" required
            class="form-select block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
            @error('category_id') is-invalid @enderror">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback text-red-600 mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="published_year" class="block mb-1 font-medium text-gray-700">Tahun Terbit</label>
        <input type="number" id="published_year" name="published_year" value="{{ old('published_year') }}" required
            class="form-control block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
            @error('published_year') is-invalid @enderror">
        @error('published_year')
            <div class="invalid-feedback text-red-600 mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="quantity" class="block mb-1 font-medium text-gray-700">Jumlah</label>
        <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required
            class="form-control block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
            @error('quantity') is-invalid @enderror">
        @error('quantity')
            <div class="invalid-feedback text-red-600 mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="description" class="block mb-1 font-medium text-gray-700">Deskripsi</label>
        <textarea id="description" name="description"
            class="form-control block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
            @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
        @error('description')
            <div class="invalid-feedback text-red-600 mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="image" class="block mb-1 font-medium text-gray-700">Gambar (upload file)</label>
        <input type="file" id="image" name="image"
            class="form-control block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded file:border-0
            file:text-sm file:font-semibold
            file:bg-blue-50 file:text-blue-700
            hover:file:bg-blue-100
            @error('image') is-invalid @enderror">
        @error('image')
            <div class="invalid-feedback text-red-600 mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="image_url" class="block mb-1 font-medium text-gray-700">atau URL Gambar (jika ada)</label>
        <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}"
            class="form-control block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
            @error('image_url') is-invalid @enderror">
        @error('image_url')
            <div class="invalid-feedback text-red-600 mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="flex space-x-3">
        <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
            Simpan
        </button>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
            Batal
        </a>
    </div>
</form>
@endsection
