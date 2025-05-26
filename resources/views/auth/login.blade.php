@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full bg-white rounded-3xl shadow-xl p-10 space-y-8">
    <div class="text-center">
      {{-- Logo sederhana --}}
      <svg class="mx-auto w-14 h-14 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0-1.657-1.343-3-3-3S6 9.343 6 11s1.343 3 3 3 3-1.343 3-3zm0 0c0 2.21 1.79 4 4 4h.01c2.21 0 3.99-1.79 3.99-4s-1.78-4-3.99-4H16c-2.21 0-4 1.79-4 4z"/>
      </svg>
      <h2 class="mt-4 text-3xl font-extrabold text-gray-900">SUNGKONG BOOK</h2>
      <p class="mt-2 text-sm text-gray-600">Anda Memasuki Area Dashboard Admin</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
      <div class="rounded-md bg-green-50 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L9 14.414 6.293 11.707a1 1 0 011.414-1.414L9 11.586l6.293-6.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3 text-sm font-medium text-green-800">
            {{ session('success') }}
          </div>
        </div>
      </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
      <div class="rounded-md bg-red-50 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V7a1 1 0 112 0v2a1 1 0 01-2 0zm0 4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6" novalidate>
      @csrf
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" required autocomplete="email" autofocus
          value="{{ old('email') }}"
          class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none sm:text-sm transition">
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input id="password" name="password" type="password" required autocomplete="current-password"
          class="mt-1 block w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none sm:text-sm transition">
      </div>

      <div>
        <button type="submit"
          class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl bg-indigo-600 text-white font-semibold shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
          Login
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
