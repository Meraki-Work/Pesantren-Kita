<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('styling/style.css') }}">
    <title>@yield('title', 'PesantrenKita')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/cally"></script>
    <style>[x-cloak]{ display:none !important; }</style>
    <style>
    
#logAbsen .log-item {
    display: flex;
    align-items: flex-start; /* supaya teks panjang tetap sejajar di atas */
    gap: 0.5rem;
    border-bottom: 1px solid #e5e7eb; /* garis bawah halus */
    padding-bottom: 0.5rem;
    margin-bottom: 0.5rem;
}

#logAbsen .log-item img {
    flex-shrink: 0; /* gambar tidak ikut mengecil */
    width: 3rem; /* w-12 */
    height: 3rem; /* h-12 */
    border-radius: 9999px; /* bulat sempurna */
    object-fit: cover;
    border: 2px solid #3b82f6; /* biru */
}
</style>

</head>

<body class="bg-gray-50 text-gray-800 min-h-screen overflow-x-hidden">
    @yield('content')
</body>

<!-- @if (session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Berhasil!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif -->

@if (session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Gagal!</strong>
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif
<script> function showModal(id) { document.getElementById(id).classList.remove('hidden'); } function closeModal(id) { document.getElementById(id).classList.add('hidden'); } </script>

</html>