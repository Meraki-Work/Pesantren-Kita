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

  <!-- Background -->
  <div class="absolute inset-0 bg-cover bg-center"
       style="background-image: url('{{ asset('asset/background masjid .png') }}');"></div>
  <div class="absolute inset-0 backdrop-blur-sm bg-black/20"></div>

  <div class="relative z-10 bg-[#F8FFF8]/95 w-full max-w-4xl rounded-xl shadow-lg p-10">
    <h2 class="text-2xl font-bold text-center mb-8">Registrasi Pengguna</h2>

    <div id="alert-box" class="hidden p-3 rounded text-center mb-4 text-sm"></div>

    @php
        $inputClass = 'w-full h-[45px] pl-3 pr-10 text-sm placeholder:text-sm bg-gray-100 
        border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500';
    @endphp

    <form id="registerForm" class="grid md:grid-cols-2 gap-6">
      {{-- Kiri --}}
      <div class="space-y-4">
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Nama Pengguna</label>
          <input type="text" id="username" name="username" placeholder="Masukkan Nama Pengguna" required class="{{ $inputClass }}">
          <i class="fa-solid fa-user absolute right-3 top-10 text-gray-500"></i>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Email</label>
          <input type="email" id="email" name="email" placeholder="Masukkan Email" required class="{{ $inputClass }}">
          <i class="fa-solid fa-envelope absolute right-3 top-10 text-gray-500"></i>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Role</label>
          <select id="role" name="role" required class="{{ $inputClass }}">
            <option value="">Pilih Role</option>
            <option value="Admin">Admin</option>
            <option value="Pengajar">Pengajar</option>
          </select>
          <i class="fa-solid fa-user-tag absolute right-3 top-10 text-gray-500"></i>
        </div>
      </div>

      {{-- Kanan --}}
      <div class="space-y-4">
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Kata Sandi</label>
          <input type="password" id="password" name="password" placeholder="Masukkan Kata Sandi" required minlength="6" class="{{ $inputClass }}">
          <i class="fa-solid fa-eye-slash absolute right-3 top-11 text-gray-500 cursor-pointer" onclick="togglePassword('password', this)"></i>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Konfirmasi Kata Sandi</label>
          <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi Kata Sandi" required minlength="6" class="{{ $inputClass }}">
          <i class="fa-solid fa-eye-slash absolute right-3 top-11 text-gray-500 cursor-pointer" onclick="togglePassword('password_confirmation', this)"></i>
        </div>
      </div>

      {{-- Tombol --}}
      <div class="md:col-span-2 flex flex-col items-center mt-6">
        <button type="submit"
          class="w-full md:w-[300px] bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg transition duration-300">
          Registrasi
        </button>

        <p class="text-sm text-center mt-3 text-black">
          <a href="{{ route('login') }}" class="text-black hover:underline font-medium">Kembali ke login</a>
        </p>
      </div>
    </form>
  </div>

  <script>
    // toggle password visibility
    function togglePassword(id, el) {
      const input = document.getElementById(id);
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      el.classList.toggle('fa-eye');
      el.classList.toggle('fa-eye-slash');
    }

    // Handle form submit via API
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
      e.preventDefault();

      const data = {
        username: document.getElementById('username').value,
        email: document.getElementById('email').value,
        role: document.getElementById('role').value,
        password: document.getElementById('password').value,
        password_confirmation: document.getElementById('password_confirmation').value
      };

      const alertBox = document.getElementById('alert-box');
      alertBox.classList.remove('hidden');
      alertBox.classList.remove('bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700');

      try {
        const res = await fetch('http://127.0.0.1:8000/api/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(data)
        });

        const result = await res.json();

        if (!res.ok) {
          alertBox.classList.add('bg-red-100', 'text-red-700');
          alertBox.innerText = result.message || 'Registrasi gagal. Periksa kembali data Anda.';
          return;
        }

        alertBox.classList.add('bg-green-100', 'text-green-700');
        alertBox.innerText = 'Registrasi berhasil! Cek email untuk kode OTP.';

        // Simpan email di localStorage untuk halaman verifikasi OTP
        localStorage.setItem('pendingEmail', data.email);

        // Redirect ke halaman verifikasi OTP setelah 2 detik
        setTimeout(() => {
          window.location.href = '/verify-otp';
        }, 2000);

      } catch (error) {
        alertBox.classList.add('bg-red-100', 'text-red-700');
        alertBox.innerText = 'Terjadi kesalahan server. Silakan coba lagi.';
      }
    });
  </script>

</body>
</html>
