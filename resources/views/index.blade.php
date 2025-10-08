<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('styling/style.css') }}">
    <title>PesantrenKita</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <script src="./assets/vendor/lodash/lodash.min.js"></script>
    <script src="./assets/vendor/apexcharts/dist/apexcharts.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    {{-- Navbar atau sidebar bisa di sini --}}
    
    <main class="p-6">
        @yield('tes') {{-- ini penting! --}}
    </main>

    {{-- Footer --}}
</body>
</html>
