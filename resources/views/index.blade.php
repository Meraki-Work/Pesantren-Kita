<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('styling/style.css') }}">
    <title>@yield('title', 'PesantrenKita')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen overflow-x-hidden">
    @yield('content')
</body>

@if (session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Berhasil!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if (session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Gagal!</strong>
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif


</html>