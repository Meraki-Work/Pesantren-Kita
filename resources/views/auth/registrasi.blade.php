{{-- 
Dikerjakan Oleh: Titho (3312401071) Front-End
               : Muhammad Rizky Febrian (3312401082) Back-End
Dikerjakan Pada: 8 October 2025 untuk Front-End & 10 October 2025 untuk Back-End
Deskripsi      : Membuat halaman registrasi pengguna dengan form yang terstruktur dan menarik menggunakan Tailwind CSS.
                 Form ini mencakup input untuk nama pengguna, email, kata sandi, nama pondok pesantren, dan role pengguna. 
--}}

@extends('index')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi - PesantrenKita</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
    .input-custom {
      font-family: 'Poppins';
      font-size: 15px;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center relative">

  <!-- Background gambar -->
  <div class="absolute inset-0 bg-cover bg-center"
       style="background-image: url('{{ asset('asset/masjid-hd-pc.jpg') }}');">
  </div>

  <!-- Overlay gelap -->
  <div class="absolute inset-0 bg-black/30"></div>

  <!-- Konten form -->
  <div class="relative z-10 bg-[#F8FFF8]/95 w-full max-w-4xl rounded-xl shadow-lg p-10">
    <h2 class="text-2xl font-bold text-center mb-8">Registrasi Pengguna</h2>

    @php
        $inputClass = 'w-full h-[45px] pl-3 pr-10 text-sm placeholder:text-sm bg-gray-100 
        border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500';
    @endphp

    {{-- Tampilkan pesan error --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="grid md:grid-cols-2 gap-6">
      @csrf

      {{-- Kolom kiri --}}
      <div class="space-y-4">
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Nama Pengguna</label>
          <input type="text" name="username" placeholder="Masukkan Nama Pengguna" required class="{{ $inputClass }}">
          <i class="fa-solid fa-user absolute right-3 top-10 text-gray-500"></i>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Email</label>
          <input type="email" name="email" placeholder="Masukkan Email" required class="{{ $inputClass }}">
          <i class="fa-solid fa-envelope absolute right-3 top-10 text-gray-500"></i>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Role</label>
          <select name="role" required class="{{ $inputClass }}">
            <option value="">Pilih Role</option>
            <option value="Admin">Admin</option>
            <option value="Pengajar">Pengajar</option>
          </select>
          <i class="fa-solid fa-user-tag absolute right-3 top-10 text-gray-500"></i>
        </div>
      </div>

      {{-- Kolom kanan --}}
      <div class="space-y-4">
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Kata Sandi</label>
          <input type="password" name="password" id="password" placeholder="Masukkan Kata Sandi" required minlength="6" class="{{ $inputClass }}">
          <i class="fa-solid fa-eye absolute right-3 top-10 text-gray-500 cursor-pointer" onclick="togglePassword('password', this)"></i>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Konfirmasi Kata Sandi</label>
          <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi Kata Sandi" required minlength="6" class="{{ $inputClass }}">
          <i class="fa-solid fa-eye absolute right-3 top-10 text-gray-500 cursor-pointer" onclick="togglePassword('password_confirmation', this)"></i>
        </div>
      </div>

      {{-- Tombol --}}
      <div class="md:col-span-2 flex justify-center mt-6">
        <button type="submit"
          class="w-full md:w-[300px] bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg transition duration-300">
          Registrasi
        </button>
      </div>
    </form>
  </div>

  <script>
    function togglePassword(id, el) {
      const input = document.getElementById(id);
      const icon = el;
      if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
      }
    }
  </script>

</body>
</html>
