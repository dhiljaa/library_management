@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Tambah Kategori Baru</h1>

@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.categories.store') }}" method="POST" class="max-w-md">
    @csrf
    <div class="mb-4">
        <label for="name" class="form-label font-medium">Nama Kategori</label>
        <input 
            type="text" 
            name="name" 
            id="name" 
            class="form-control @error('name') is-invalid @enderror" 
            value="{{ old('name') }}" 
            required 
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary me-2">Simpan</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
