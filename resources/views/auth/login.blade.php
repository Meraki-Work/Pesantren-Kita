{{-- 
Dikerjakan Oleh: Titho (3312401071) Front-End dan Back-End 
Dikerjakan Pada: 10 October 2025 
Deskripsi      : Membuat halaman Login pengguna dengan form yang terstruktur dan menarik menggunakan Tailwind CSS.
                 Form ini mencakup input untuk email dan kata sandi. 
--}}

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  {{-- CSRF token untuk request fetch yang akan membuat session dari token API --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login - PesantrenKita</title>

  {{-- Import icon dan font --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  {{-- Script utama untuk toggle password dan proses login --}}
  <script defer>
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const toggle = document.getElementById('togglePassword');
    const pass = document.getElementById('kataSandi');

    if (!form) {
      console.error("Form login tidak ditemukan di halaman.");
      return;
    }

    // Toggle password
    toggle.addEventListener('click', () => {
      const type = pass.getAttribute('type') === 'password' ? 'text' : 'password';
      pass.setAttribute('type', type);
      toggle.classList.toggle('fa-eye');
      toggle.classList.toggle('fa-eye-slash');
    });

    // Submit form login
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const email = document.getElementById('email').value;
      const password = pass.value;

      const response = await fetch('/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ email, password })
      });

      const data = await response.json();
      console.log('Data:', data);

      if (response.ok) {
        localStorage.setItem('token', data.token);

          // Tukarkan token API menjadi session web supaya server membuat session
        try {
          const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

          const res2 = await fetch('/session/create', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrf
            },
            credentials: 'same-origin',
            body: JSON.stringify({ token: data.token })
          });

          // Redirect berdasarkan role
          const role = (data.user && data.user.role) ? data.user.role.toLowerCase() : null;
          if (res2.ok) {
            if (role === 'admin' || role === 'pengajar') {
              window.location.href = '/kepegawaian';
            } else {
              window.location.href = '/';
            }
          } else {
            // fallback: redirect based on role (even if session create failed)
            if (role === 'admin' || role === 'pengajar') window.location.href = '/kepegawaian';
            else window.location.href = '/';
          }
        } catch (err) {
          console.error('Gagal membuat session dari token:', err);
          const role = (data.user && data.user.role) ? data.user.role.toLowerCase() : null;
          if (role === 'admin' || role === 'pengajar') window.location.href = '/kepegawaian';
          else window.location.href = '/';
        }
      } else {
        alert(data.message || 'Login gagal');
      }
    });
  });
  </script>

  {{-- Style dasar halaman --}}
  <style>
    body { font-family: 'Poppins'; }
    .input-custom { font-size: 15px; }
  </style>
</head>

<body class="min-h-screen flex">

  {{-- ======== Bagian Kiri: Background Masjid ======== --}}
  <div class="w-1/2 relative hidden md:block">
    <img src="{{ asset('asset/foto_masjid2.png') }}" alt="Background Masjid" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-white">
      <img src="{{ asset('asset/Frame 40.png') }}" alt="Logo" class="w-24 mb-4">
      <h1 class="text-3xl font-bold">PesantrenKita</h1>
    </div>
  </div>

  {{-- ======== Bagian Kanan: Form Login ======== --}}
  <div class="w-full md:w-1/2 flex items-center justify-center bg-[#344E41]">
    <div class="bg-white bg-opacity-90 p-6 rounded-xl shadow-md max-w-md w-[400px] h-[450px] flex flex-col items-center justify-center">
      
      {{-- Judul Form --}}
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-black">Masuk</h2>
      </div>

      {{-- Styling input dinamis pakai Blade --}}
      @php
          $inputClass = 'w-full h-[40px] pl-3 pr-10 text-sm placeholder:text-sm bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500';
      @endphp

      {{-- Form login — fallback ke server-side jika JS gagal --}}
      <form id="loginForm" action="{{ route('login') }}" method="POST" class="space-y-4 flex flex-col items-center w-full">
        @csrf
        
        {{-- Input Email --}}
        <div class="relative w-full max-w-[300px]">
          <h3 class="mb-1 text-sm font-medium">Email</h3>
          <input type="email" id="email" name="email" class="{{ $inputClass }}" placeholder="Masukkan email" required>
          <i class="fa-solid fa-envelope absolute right-3 top-9 text-gray-500"></i>
        </div>

        {{-- Input Password + Toggle --}}
        <div class="relative w-full max-w-[300px]">
          <h3 class="mb-1 text-sm font-medium">Kata Sandi</h3>
          <input type="password" id="kataSandi" name="kata_sandi" class="{{ $inputClass }}" placeholder="Masukkan kata sandi" required>
          {{-- Ikon mata untuk toggle password --}}
          <i id="togglePassword" class="fa-solid fa-eye-slash absolute right-3 top-9 text-gray-500 cursor-pointer"></i>
        </div>

        {{-- Tombol Masuk --}}
        <button 
          type="submit"
          class="w-full max-w-[300px] bg-green-500 hover:bg-green-600 active:bg-green-700 focus:ring-2 focus:ring-green-300 transition duration-200 text-white font-semibold py-2 rounded-lg">
          Masuk
        </button>
      </form>

      {{-- Link Lupa Kata Sandi --}}
      <p class="text-center text-sm text-gray-600 mt-4">
          <a href="{{ route('lupakatasandi') }}" class="hover:underline">Lupa kata sandi?</a>
      </p>

      {{-- Link Registrasi --}}
      <p class="text-center text-sm text-gray-600 mt-2">
          <a href="{{ route('registrasi.index') }}" class="hover:underline">Belum punya akun?</a>
      </p>

    </div>
  </div>
</body>
</html>
