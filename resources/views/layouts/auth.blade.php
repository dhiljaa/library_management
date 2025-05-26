<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Login')</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Bootstrap 5 CDN (optional, jika masih dipakai di layout lain) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900 antialiased">
    <div class="container mt-5">
        @yield('content')
    </div>
</body>
</html>
