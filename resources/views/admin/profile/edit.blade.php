@extends('admin.layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-3xl mx-auto">
    <h2 class="text-2xl font-semibold mb-6">Edit Profile</h2>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 text-green-700 bg-green-100 rounded border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $user->name) }}" 
                required
                class="block w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                    @error('name') border-red-500 @enderror"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email', $user->email) }}" 
                required
                class="block w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                    @error('email') border-red-500 @enderror"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-500 text-xs">(Kosongkan jika tidak ingin diubah)</span></label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                autocomplete="new-password"
                class="block w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                    @error('password') border-red-500 @enderror"
            >
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                autocomplete="new-password"
                class="block w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            >
        </div>

        <div>
            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Avatar (gambar)</label>
            <input 
                type="file" 
                id="avatar" 
                name="avatar" 
                accept="image/*"
                class="block w-full text-gray-600 file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0
                       file:text-sm file:font-semibold
                       file:bg-indigo-50 file:text-indigo-700
                       hover:file:bg-indigo-100
                       @error('avatar') border-red-500 @enderror"
            >
            @error('avatar')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            @if($user->avatar)
                <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="Avatar" class="mt-4 w-24 h-24 rounded-full object-cover border border-gray-300" />
            @endif
        </div>

        <div>
            <button type="submit" class="inline-block px-6 py-2 rounded-md bg-indigo-600 text-white font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Update Profile
            </button>
        </div>
    </form>
</div>
@endsection
