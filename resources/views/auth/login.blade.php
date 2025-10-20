{{-- 
Dikerjakan Oleh: Titho (3312401071) Front-End dan Back-End 
Dikerjakan Pada: 10 October 2025 
Deskripsi      : Membuat halaman Login pengguna dengan form yang terstruktur dan menarik menggunakan Tailwind CSS.
                 Form ini mencakup input untuk email dan kata sandi. 
--}}

@extends('index')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - PesantrenKita</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    .input-custom {
      font-family: 'Poppins';
      font-size: 15px;
    }
  </style>
</head>
<body class="min-h-screen flex">

  <!-- Kiri: Background Masjid -->
  <div class="w-1/2 relative hidden md:block">
    <img src="{{ asset('asset/foto_masjid2.png') }}" alt="Background Masjid" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-white">
      <img src="{{ asset('asset/Frame 40.png') }}" alt="Logo" class="w-24 mb-4">
      <h1 class="text-3xl font-bold">PesantrenKita</h1>
    </div>
  </div>

  <!-- Kanan: Form Login -->
  <div class="w-full md:w-1/2 flex items-center justify-center bg-[#344E41]">
    <div class="bg-white bg-opacity-90 p-6 rounded-xl shadow-md max-w-md w-[400px] h-[450px] flex flex-col items-center justify-center">
      
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-black">Masuk</h2>
      </div>

      @php
          $inputClass = 'w-full h-[40px] pl-3 pr-10 text-sm placeholder:text-sm bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500';
      @endphp

      {{-- ✅ Flash Message Berhasil --}}
      @if(session('success'))
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-3 max-w-[300px] w-full text-center text-sm">
              {{ session('success') }}
          </div>
      @endif

      {{-- ⚠️ Pesan Error --}}
      @if ($errors->any())
          <div class="bg-red-100 text-red-700 p-2 rounded mb-3 max-w-[300px] w-full text-center text-sm">
              {{ $errors->first() }}
          </div>
      @endif

      <form action="{{ route('login') }}" method="POST" class="space-y-4 flex flex-col items-center w-full">
          @csrf

          <!-- Input Nama email -->
            <div class="relative w-full max-w-[300px]">
              <h3 class="mb-1 text-sm font-medium">Email</h3>
              <input type="email" name="email" placeholder="Masukkan Email" class="{{ $inputClass }}" required>
              <i class="fa-solid fa-envelope absolute right-3 top-9 text-gray-500"></i>
            </div>

          <!-- Input Kata Sandi dengan Toggle -->
          <div class="relative w-full max-w-[300px]">
              <h3 class="mb-1 text-sm font-medium">Kata Sandi</h3>
              <input type="password" id="kataSandi" name="kata_sandi" placeholder="Masukkan Kata Sandi" class="{{ $inputClass }}" required>
              <i class="fa-solid fa-eye toggle-icon absolute right-3 top-9 text-gray-500" id="togglePassword"></i>
          </div>

          <!-- Tombol Masuk -->
          <button type="submit"
              class="w-full max-w-[300px] flex justify-center bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg transition duration-300 text-sm">
              Masuk
          </button>
      </form>

      <!-- Link Lupa Kata Sandi -->
      <p class="text-center text-sm text-gray-600 mt-4">
          <a href="{{ route('lupakatasandi') }}" class="hover:underline">Lupa Kata Sandi?</a>
      </p>

      <!-- Link Belum punya akun -->
      <p class="text-center text-sm text-gray-600 mt-2">
          <a href="{{ route('registrasi.index') }}" class="hover:underline">Belum punya akun?</a>
      </p>

    </div>
  </div>
<!-- Script Toggle Password -->
  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('kataSandi');

    togglePassword.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      togglePassword.classList.toggle('fa-eye');
      togglePassword.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>
