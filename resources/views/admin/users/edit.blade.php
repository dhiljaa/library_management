@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<h1 class="text-3xl font-semibold mb-8">Edit User</h1>

@if($errors->any())
    <div class="mb-6 px-5 py-4 rounded border border-red-400 bg-red-100 text-red-800 relative" role="alert">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button 
            type="button" 
            onclick="this.parentElement.style.display='none';" 
            class="absolute top-3 right-3 text-red-800 hover:text-red-900 focus:outline-none"
            aria-label="Close"
        >
            &times;
        </button>
    </div>
@endif

<form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto space-y-8 bg-white p-8 rounded shadow-md">
    @csrf
    @method('PUT')

    <div>
        <label for="name" class="block mb-2 font-medium text-gray-800">Nama</label>
        <input 
            type="text" 
            name="name" 
            id="name" 
            value="{{ old('name', $user->name) }}" 
            required
            class="w-full rounded-md border border-gray-300 px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
            placeholder="Masukkan nama user"
        >
    </div>

    <div>
        <label for="email" class="block mb-2 font-medium text-gray-800">Email</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            value="{{ old('email', $user->email) }}" 
            required
            class="w-full rounded-md border border-gray-300 px-4 py-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
            placeholder="Masukkan email user"
        >
    </div>

    <div>
        <label for="role" class="block mb-2 font-medium text-gray-800">Role</label>
        <select 
            name="role" 
            id="role" 
            required
            class="w-full rounded-md border border-gray-300 px-4 py-3 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
        >
            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
            <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>

    <div>
        <label for="avatar" class="block mb-2 font-medium text-gray-800">Avatar (Opsional)</label>
        @if ($user->avatar && file_exists(public_path('storage/avatars/' . $user->avatar)))
            <div class="mb-4">
                <img 
                    src="{{ asset('storage/avatars/' . $user->avatar) }}" 
                    alt="Avatar {{ $user->name }}" 
                    class="w-28 h-28 object-cover rounded-full border border-gray-300 shadow"
                >
            </div>
        @else
            <div class="mb-4 flex items-center justify-center w-28 h-28 bg-gray-100 rounded-full border border-gray-300 text-gray-400 select-none">
                No Avatar
            </div>
        @endif
        <input 
            type="file" 
            name="avatar" 
            id="avatar"
            accept=".jpg,.jpeg,.png"
            class="w-full rounded-md border border-gray-300 px-4 py-3 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
        >
        @error('avatar')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex space-x-4 justify-end">
        <a 
            href="{{ route('admin.users.index') }}" 
            class="inline-block px-6 py-3 bg-blue-200 text-blue-800 rounded-md hover:bg-blue-300 transition"
        >
            Kembali
        </a>
        <button 
            type="submit" 
            class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
        >
            Simpan
        </button>
    </div>
</form>
@endsection
