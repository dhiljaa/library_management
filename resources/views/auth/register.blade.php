@extends('layouts.auth')


@section('title', 'Register')

@section('content')
<h1>Register</h1>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input id="name" type="text" 
               class="form-control @error('name') is-invalid @enderror" 
               name="name" value="{{ old('name') }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input id="password_confirmation" type="password" 
               class="form-control @error('password_confirmation') is-invalid @enderror" 
               name="password_confirmation" required>
        @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Daftar</button>
    <a href="{{ route('login') }}" class="btn btn-link">Sudah punya akun? Login</a>
</form>
@endsection
