{{-- 
    Nama file   : landing.blade.php
    Deskripsi   : Layout utama halaman landing yang memuat struktur dasar website, termasuk navbar, konten utama, dan footer.
    Dibuat oleh : Zahrah Nazihah Ginting - NIM: 3312401077
    Tanggal     : 13 Oktober 2025
--}}

{{-- resources/views/layouts/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PesantrenKita' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800">
    {{-- Navbar --}}
    <x-navbar />

    {{-- Main Content (isi halaman) --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />
</body>
</html>
