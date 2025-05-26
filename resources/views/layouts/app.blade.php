<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            {{-- Brand dan Link Dashboard bisa disesuaikan --}}
            <a class="navbar-brand" href="{{ auth()->check() 
                ? (auth()->user()->role === 'admin' 
                    ? route('admin.books.index') 
                    : (auth()->user()->role === 'staff' 
                        ? route('staff.loans.index') 
                        : '#')
                  )
                : '#' }}">
                {{ auth()->check() ? ucfirst(auth()->user()->role) . ' - Sungokong Book' : 'Sungokong Book' }}
            </a>

            {{-- Tombol collapse tetap muncul --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            @auth
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        @php $currentRoute = Route::currentRouteName(); @endphp

                        {{-- Menu untuk Admin --}}
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ str_starts_with($currentRoute, 'admin.books') ? 'active' : '' }}" href="{{ route('admin.books.index') }}">Kelola Buku</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ str_starts_with($currentRoute, 'admin.loans') ? 'active' : '' }}" href="{{ route('admin.loans.index') }}">Kelola Peminjaman</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ str_starts_with($currentRoute, 'admin.categories') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Kelola Kategori</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ str_starts_with($currentRoute, 'admin.users') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Kelola User</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $currentRoute === 'admin.statistik' ? 'active' : '' }}" href="{{ route('admin.statistik') }}">Statistik</a>
                            </li>
                        @endif

                        {{-- Menu untuk Staff --}}
                        @if(auth()->user()->role === 'staff')
                            <li class="nav-item">
                                <a class="nav-link {{ str_starts_with($currentRoute, 'staff.loans') ? 'active' : '' }}" href="{{ route('staff.loans.index') }}">Peminjaman Staff</a>
                            </li>
                        @endif

                        {{-- Menu lain bisa ditambahkan di sini untuk user, dll --}}
                    </ul>

                    <div class="d-flex align-items-center">
                        <span class="me-3">Halo, {{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> -->
