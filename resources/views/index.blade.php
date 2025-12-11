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

    <style>
        #logAbsen .log-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
        }

        #logAbsen .log-item img {
            flex-shrink: 0;
            width: 3rem;
            height: 3rem;
            border-radius: 9999px;
            object-fit: cover;
            border: 2px solid #3b82f6;
        }
    </style>

</head>

<body class="bg-gray-50 text-gray-800 min-h-screen overflow-x-hidden">
    @yield('content')
</body>

@if (session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Gagal!</strong>
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

</html>