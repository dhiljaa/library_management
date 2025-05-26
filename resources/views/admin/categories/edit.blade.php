@extends('admin.layouts.app')
@section('title', 'Edit Kategori')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Edit Kategori</h1>

@if ($errors->any())
    <div class="mb-4 p-4 border border-red-300 bg-red-100 text-red-700 rounded">
        <ul class="list-disc list-inside mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="max-w-md">
    @csrf
    @method('PUT')

    <div class="mb-6">
        <label for="name" class="block font-medium mb-1">Nama Kategori</label>
        <input 
            type="text" 
            name="name" 
            id="name" 
            value="{{ old('name', $category->name) }}" 
            required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500
            @error('name') border-red-500 @else border-gray-300 @enderror"
        >
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" 
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition me-2">
        Update
    </button>
    <a href="{{ route('admin.categories.index') }}" 
       class="inline-block px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
        Batal
    </a>
</form>
@endsection
