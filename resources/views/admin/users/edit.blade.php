@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Edit User</h1>

@if($errors->any())
    <div class="mb-4 px-4 py-3 rounded border border-red-300 bg-red-100 text-red-700 relative" role="alert">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button 
            type="button" 
            onclick="this.parentElement.style.display='none';" 
            class="absolute top-1 right-2 text-red-700 hover:text-red-900 focus:outline-none"
            aria-label="Close"
        >
            &times;
        </button>
    </div>
@endif

<form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="max-w-md space-y-6">
    @csrf
    @method('PUT')

    <div>
        <label for="name" class="block mb-1 font-medium text-gray-700">Nama</label>
        <input 
            type="text" 
            name="name" 
            id="name" 
            value="{{ old('name', $user->name) }}" 
            required
            class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
    </div>

    <div>
        <label for="email" class="block mb-1 font-medium text-gray-700">Email</label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            value="{{ old('email', $user->email) }}" 
            required
            class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
    </div>

    <div>
        <label for="role" class="block mb-1 font-medium text-gray-700">Role</label>
        <select 
            name="role" 
            id="role" 
            required
            class="w-full rounded border border-gray-300 px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
            <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>

    <div class="flex space-x-3">
        <button 
            type="submit" 
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
        >
            Simpan
        </button>
        <a 
            href="{{ route('admin.users.index') }}" 
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition flex items-center justify-center"
        >
            Kembali
        </a>
    </div>
</form>
@endsection
