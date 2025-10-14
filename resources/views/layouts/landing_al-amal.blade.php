<!-- 
 Nama file   : landing_al-amal.blade.php
 Deskripsi   : File landing_al-amal.blade.php berfungsi sebagai layout utama (template dasar) untuk halaman depan 
               website Pondok Pesantren Al Amal Batam.
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 Oktober 2025
--> 

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Pesantren Al Amal Batam</title>
    @vite('resources/css/app.css')
</head>

<body class="antialiased bg-white text-gray-800">

    {{-- NAVBAR --}}
    <x-al-amal.navbar />

    {{-- CAROUSEL --}}
    <x-al-amal.carousel />

    {{-- CARD ABOUT --}}
    <x-al-amal.card_about />

    {{-- CARD GALERI --}}
    <x-al-amal.card_galeri />

    {{-- FOOTER --}}
    <x-al-amal.footer />

    
    @vite('resources/js/app.js')

</body>
</html>
