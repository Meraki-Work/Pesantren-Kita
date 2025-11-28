<!-- 
 Nama file   : kelola_landing_ponpes.blade.php
 Deskripsi   : Layout ini berfungsi sebagai kerangka utama halaman mengelola isi landing page, tempat sidebar dan konten halaman lain ditampilkan.
 Dibuat oleh : Muhammad Rizky Febrian - NIM: 3312401082
 Tanggal     : 27 November 2025
--> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Landing - PesantrenKita</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-white font-sans text-gray-800">
    <div class="flex min-h-screen">

        @include('components.sidebar')

        <main class="flex-1 p-8">
            @yield('content')
        </main>

    </div>
</body>
</html>
