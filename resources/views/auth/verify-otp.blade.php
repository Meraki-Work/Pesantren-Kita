@extends('index')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi OTP</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center relative">
  
  <!-- Background -->
  <div class="absolute inset-0 bg-cover bg-center"
       style="background-image: url('{{ asset('asset/background masjid .png') }}');"></div>
  <div class="absolute inset-0 backdrop-blur-sm bg-black/20"></div>
  
  <div class="relative z-10 bg-[#F8FFF8]/95 w-full max-w-md rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center mb-12">Verifikasi Kode OTP</h2>

    <div id="alert-box" class="hidden p-3 rounded mb-6 text-center text-sm"></div>

    <form id="otpForm" class="space-y-6">
      <!-- Input OTP -->
      <div class="relative w-full">
    <label class="block text-sm font-medium mb-2">Kode OTP</label>

    <input
        type="text"
        placeholder="Masukkan 6 digit OTP"
        class="w-full h-12 p-3 pr-12 rounded-lg border border-[#C3C8C5] bg-gray-200
               focus:ring-2 focus:ring-green-500 focus:outline-none"
    >

    <!-- ICON KEY PRESISI TENGAH -->
    <i class="fa-solid fa-key absolute right-4 top-[45px] text-gray-500"></i>
</div>


      <!-- Tombol -->
      <div class="flex justify-center">
        <button type="submit"
          class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg transition duration-300">
          Verifikasi
        </button>
      </div>
    </form>
  </div>

  <script>
    const otpForm = document.getElementById('otpForm');
    const alertBox = document.getElementById('alert-box');

    otpForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const email = localStorage.getItem('pendingEmail'); // ambil email dari localStorage
      const otp = document.getElementById('otp').value.trim();

      if (!email) {
        showAlert('Email tidak ditemukan. Silakan registrasi ulang.', 'error');
        return;
      }

      try {
        const res = await fetch('http://127.0.0.1:8000/api/verify-otp', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({ email, otp })
        });

        const result = await res.json();

        if (!res.ok) {
          showAlert(result.message || 'Kode OTP salah atau sudah kedaluwarsa.', 'error');
          return;
        }

        showAlert('Verifikasi berhasil! Mengarahkan ke halaman login...', 'success');
        localStorage.removeItem('pendingEmail'); // hapus data email

        setTimeout(() => {
          window.location.href = '/login';
        }, 2000);

      } catch (error) {
        showAlert('Terjadi kesalahan server. Silakan coba lagi.', 'error');
      }
    });

    function showAlert(message, type) {
      alertBox.innerText = message;
      alertBox.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700');

      if (type === 'error') {
        alertBox.classList.add('bg-red-100', 'text-red-700');
      } else {
        alertBox.classList.add('bg-green-100', 'text-green-700');
      }
    }
  </script>

</body>
</html>
