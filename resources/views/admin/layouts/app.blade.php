<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Optional: Bootstrap Icons CDN -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
  />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<nav class="bg-white border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('admin.books.index') }}" class="text-xl font-semibold text-indigo-600 hover:text-indigo-700">
                Admin - Sungokong Book
            </a>

            <!-- Mobile menu button -->
            <button id="mobile-menu-button" class="lg:hidden p-2 rounded-md text-gray-500 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-600" aria-label="Toggle menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex lg:space-x-6">
                @php
                    $currentRoute = Route::currentRouteName();
                    $user = auth()->user();
                    $avatarUrl = $user->avatar 
                        ? asset('storage/avatars/' . $user->avatar) 
                        : asset('images/default-avatar.png'); 
                @endphp

                <a href="{{ route('admin.books.index') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ str_starts_with($currentRoute, 'admin.books') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:text-indigo-600' }}">
                   Kelola Buku
                </a>
                <a href="{{ route('admin.loans.index') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ str_starts_with($currentRoute, 'admin.loans') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:text-indigo-600' }}">
                   Kelola Peminjaman
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ str_starts_with($currentRoute, 'admin.categories') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:text-indigo-600' }}">
                   Kelola Kategori
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:text-indigo-600' }}">
                   Kelola User
                </a>
                <a href="{{ route('admin.statistik') }}"
                   class="px-3 py-2 rounded-md text-sm font-medium {{ $currentRoute === 'admin.statistik' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:text-indigo-600' }}">
                   Statistik
                </a>
            </div>

            <!-- User Dropdown -->
            <div class="relative ml-4">
                <button id="userDropdownButton" class="flex items-center space-x-2 focus:outline-none focus:ring-2 focus:ring-indigo-600 rounded-md" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ $avatarUrl }}" alt="Avatar" class="w-9 h-9 rounded-full border border-gray-300 object-cover" />
                    <span class="text-gray-700 font-medium">Halo, {{ $user->name }}</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9l6 6 6-6"></path>
                    </svg>
                </button>
                <ul id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg py-1 hidden z-20" role="menu" aria-label="User menu">
                    <li>
                        <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-100" role="menuitem">Edit Profile</a>
                    </li>
                    <li><hr class="border-gray-200 my-1"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-100" role="menuitem">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide with JS -->
    <div id="mobileMenu" class="lg:hidden hidden border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('admin.books.index') }}"
               class="block px-3 py-2 rounded-md text-base font-medium {{ str_starts_with($currentRoute, 'admin.books') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
               Kelola Buku
            </a>
            <a href="{{ route('admin.loans.index') }}"
               class="block px-3 py-2 rounded-md text-base font-medium {{ str_starts_with($currentRoute, 'admin.loans') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
               Kelola Peminjaman
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="block px-3 py-2 rounded-md text-base font-medium {{ str_starts_with($currentRoute, 'admin.categories') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
               Kelola Kategori
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="block px-3 py-2 rounded-md text-base font-medium {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
               Kelola User
            </a>
            <a href="{{ route('admin.statistik') }}"
               class="block px-3 py-2 rounded-md text-base font-medium {{ $currentRoute === 'admin.statistik' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-600' }}">
               Statistik
            </a>
        </div>
    </div>
</nav>

<main class="flex-grow container mx-auto px-4 py-6">
    @yield('content')
</main>

<script>
    // Toggle mobile menu
    const mobileMenuBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenuBtn?.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Toggle user dropdown menu
    const userDropdownBtn = document.getElementById('userDropdownButton');
    const userDropdown = document.getElementById('userDropdown');
    userDropdownBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        userDropdown.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!userDropdownBtn.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.add('hidden');
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>