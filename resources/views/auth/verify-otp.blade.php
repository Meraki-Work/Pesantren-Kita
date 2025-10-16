{{-- 
Dikerjakan Oleh: Titho (3312401071) Front-End
               : Muhammad Rizky Febrian (3312401082) Back-End
Dikerjakan Pada: 10 October 2025 untuk Front-End & 10 October 2025 untuk Back-End
Deskripsi      : Membuat halaman verify-otp pengguna dengan form yang terstruktur dan menarik menggunakan Tailwind CSS.
                 Form ini mencakup input untuk otp. 
--}}

@extends('index')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi OTP</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-[#344E41]">

  <div class="bg-[#F8FFF8] w-full max-w-md rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center mb-12">Verifikasi Kode OTP</h2>

    @if(session('success'))
      <div class="mb-4 text-green-600 text-center font-medium">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="mb-4 text-red-600 text-center font-medium">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('verify.otp') }}" method="POST">
      @csrf

      <!-- Email (hidden) -->
      <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

      <!-- Input OTP -->
      <div class="mb-8">
        <label class="block text-sm font-medium mb-2">Kode OTP</label>
        <input type="text" name="otp" placeholder="Masukkan 6 digit OTP"
          required
          class="w-full p-3 rounded-lg border border-[#C3C8C5] bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <!-- Tombol Verifikasi -->
      <div class="flex justify-center">
        <button type="submit"
          class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg transition duration-300">
          Verifikasi
        </button>
      </div>
    </form>
  </div>

</body>
</html>
