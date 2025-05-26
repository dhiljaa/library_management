@extends('admin.layouts.app')

@section('title', 'Edit Buku')

@section('content')
<h1 class="text-3xl font-semibold mb-6">Edit Buku</h1>

<form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-3xl">
    @csrf
    @method('PUT')

    <div>
        <label for="title" class="form-label block mb-2 font-medium text-gray-700">Judul</label>
        <input type="text" 
            class="form-control @error('title') is-invalid @enderror border border-gray-300 rounded-md p-2 w-full 
            focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            id="title" name="title"
            value="{{ old('title', $book->title) }}" required>
        @error('title')
            <div class="invalid-feedback text-red-600 mt-1 text-sm">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="author" class="form-label block mb-2 font-medium text-gray-700">Penulis</label>
        <input type="text" 
            class="form-control @error('author') is-invalid @enderror border border-gray-300 rounded-md p-2 w-full 
            focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            id="author" name="author"
            value="{{ old('author', $book->author) }}" required>
        @error('author')
            <div class="invalid-feedback text-red-600 mt-1 text-sm">{{ $message }}</div>
        @enderror
    </div>

    {{-- Kategori dropdown --}}
    <div>
        <label for="category_id" class="form-label block mb-2 font-medium text-gray-700">Kategori</label>
        <select 
            class="form-select @error('category_id') is-invalid @enderror border border-gray-300 rounded-md p-2 w-full 
            focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            id="category_id" name="category_id" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback text-red-600 mt-1 text-sm">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="published_year" class="form-label block mb-2 font-medium text-gray-700">Tahun Terbit</label>
        <input type="number" 
            class="form-control @error('published_year') is-invalid @enderror border border-gray-300 rounded-md p-2 w-full 
            focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            id="published_year" name="published_year"
            value="{{ old('published_year', $book->published_year) }}" required>
        @error('published_year')
            <div class="invalid-feedback text-red-600 mt-1 text-sm">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="quantity" class="form-label block mb-2 font-medium text-gray-700">Jumlah</label>
        <input type="number" 
            class="form-control @error('quantity') is-invalid @enderror border border-gray-300 rounded-md p-2 w-full 
            focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            id="quantity" name="quantity"
            value="{{ old('quantity', $book->quantity) }}" required>
        @error('quantity')
            <div class="invalid-feedback text-red-600 mt-1 text-sm">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="description" class="form-label block mb-2 font-medium text-gray-700">Deskripsi</label>
        <textarea 
            class="form-control @error('description') is-invalid @enderror border border-gray-300 rounded-md p-2 w-full 
            focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            id="description" name="description" rows="4">{{ old('description', $book->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback text-red-600 mt-1 text-sm">{{ $message }}</div>
        @enderror
    </div>

    {{-- Upload file gambar baru --}}
    <div>
        <label for="image" class="form-label block mb-2 font-medium text-gray-700">Ganti Gambar (upload file baru)</label>
        <input type="file" 
            class="form-control @error('image') is-invalid @enderror border border-gray-300 rounded-md p-2 w-full
            focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            id="image" name="image">
        @error('image')
            <div class="invalid-feedback text-red-600 mt-1 text-sm">{{ $message }}</div>
        @enderror
        @if ($book->image_url)
            <div class="mt-2">
                <small class="text-gray-600">Gambar saat ini:</small><br>
                <img src="{{ asset($book->image_url) }}" alt="Current Image" class="rounded-md shadow-md max-h-36 mt-1">
            </div>
        @endif
    </div>

    <div>
        <label for="image_url" class="form-label block mb-2 font-medium text-gray-700">atau URL Gambar (ganti dengan URL baru)</label>
        <input type="url" 
            class="form-control @error('image_url') is-invalid @enderror border border-gray-300 rounded-md p-2 w-full
            focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            id="image_url" name="image_url"
            value="{{ old('image_url') }}">
        @error('image_url')
            <div class="invalid-feedback text-red-600 mt-1 text-sm">{{ $message }}</div>
        @enderror
    </div>

    <div class="flex space-x-3">
        <button type="submit" class="btn btn-primary px-6 py-2 rounded-md shadow hover:bg-blue-600 transition">
            Perbarui
        </button>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary px-6 py-2 rounded-md shadow hover:bg-gray-600 transition text-white">
            Batal
        </a>
    </div>
</form>
@endsection
