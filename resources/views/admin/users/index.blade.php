@extends('admin.layouts.app')

@section('title', 'Kelola User')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Kelola User</h1>

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

@if(session('error'))
    <div class="mb-4 px-4 py-3 rounded border border-red-300 bg-red-100 text-red-700 relative" role="alert">
        {{ session('error') }}
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

<div class="overflow-x-auto">
    <table class="min-w-full border border-gray-300 table-auto">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-700">Nama</th>
                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-700">Email</th>
                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-700">Role</th>
                <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-700">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="even:bg-gray-50">
                <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ ucfirst($user->role) }}</td>
                <td class="border border-gray-300 px-4 py-2 space-x-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                       class="inline-block px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                        Edit
                    </a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="inline-block px-3 py-1.5 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition"
                        >
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-gray-500 italic">Tidak ada user.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links('pagination::tailwind') }}
</div>
@endsection
