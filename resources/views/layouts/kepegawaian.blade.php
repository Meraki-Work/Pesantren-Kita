<!-- 
 Nama file   : kepegawaian.blade.php
 Deskripsi   : Layout ini berfungsi sebagai kerangka utama halaman, tempat sidebar dan konten halaman lain ditampilkan.
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 November 2025
--> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kepegawaian - PesantrenKita</title>
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
