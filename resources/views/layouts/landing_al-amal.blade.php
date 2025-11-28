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
    <x-al-amal.carousel :carousels="$carousels"/>

    {{-- ABOUT --}}
    <section id="about" class="bg-[#344E41] text-white text-center py-16 px-4">
        <h2 class="text-2xl md:text-3xl font-semibold mb-4">PesantrenKita</h2>
        <p class="max-w-3xl mx-auto leading-relaxed text-gray-200">
            PesantrenKita adalah aplikasi manajemen berbasis web yang membantu pondok pesantren 
            dalam mengelola data santri, keuangan, dan kegiatan secara terpusat. 
            Melalui sistem ini, proses administrasi menjadi lebih efisien, transparan, dan mudah diakses.
        </p>
    </section>

    {{-- CARD ABOUT --}}
    <x-al-amal.card_about :abouts="$abouts" />

    {{-- CARD GALERI --}}
    <x-al-amal.card_galeri :galleries="$galleries" />

    {{-- FOOTER --}}
    <x-al-amal.footer :footer="$footer" />

    
    @vite('resources/js/app.js')

</body>
</html>
