<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PesantrenKita - Landing Page</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    @vite('resources/css/app.css')
</head>

<body class="font-[Montserrat] bg-white">

    {{-- NAVBAR --}}
    <x-navbar />

    {{-- CAROUSEL --}}
    <x-carousel />

    {{-- ABOUT --}}
    <section id="about" class="bg-[#344E41] text-white text-center py-16 px-4">
        <h2 class="text-2xl md:text-3xl font-semibold mb-4">Apa Itu PesantrenKita?</h2>
        <p class="max-w-3xl mx-auto leading-relaxed text-gray-200">
            PesantrenKita adalah aplikasi manajemen berbasis web yang membantu pondok pesantren 
            dalam mengelola data santri, keuangan, dan kegiatan secara terpusat. 
            Melalui sistem ini, proses administrasi menjadi lebih efisien, transparan, dan mudah diakses.
        </p>
    </section>

    {{-- CARD UTAMA (FITUR + KELEBIHAN) --}}
    <x-card_utama />

    {{-- FOOTER --}}
    <x-footer />

</body>
</html>
