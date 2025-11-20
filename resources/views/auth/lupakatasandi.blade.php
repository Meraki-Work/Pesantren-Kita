{{-- 
Dikerjakan Oleh: Titho (3312401071) Front-End
               : Muhammad Rizky (3312401071) Back-End
Dikerjakan Pada: 10 October 2025 (Front-End) & 16 October 2025 (Back-End) 
Deskripsi      : Halaman Lupa Kata Sandi dengan API
--}}

@extends('index')

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Kata Sandi</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Poppins', sans-serif; }
    .input-custom { font-family: 'Poppins'; font-size: 15px; }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center relative">

  <div class="absolute inset-0 bg-cover bg-center"
       style="background-image: url('{{ asset('asset/background masjid .png') }}');">
  </div>
  <div class="absolute inset-0 backdrop-blur-sm bg-black/20"></div>

  <div class="relative z-10 bg-[#F8FFF8]/95 w-full max-w-md rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center mb-10">Lupa Kata Sandi</h2>

    @php
      $inputClass = 
      'w-full h-[45px] pl-3 pr-10 text-sm placeholder:text-sm bg-gray-100 border border-[#C3C8C5] 
       rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500';
    @endphp

    {{-- STEP 1: EMAIL --}}
    <div id="step-email" class="space-y-5">
      <div class="relative">
        <label class="block text-sm font-medium mb-2">Email</label>
        <input type="email" id="email" placeholder="Masukkan Email" class="{{ $inputClass }}">
        <i class="fa-solid fa-envelope absolute right-3 top-10 text-gray-500"></i>
      </div>

      <button id="btn-send-otp" onclick="sendOtp()"
        class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg duration-300">
        Kirim OTP
      </button>

      <p class="text-sm text-center mt-3 text-black">
        <a href="{{ route('login') }}" class="text-black hover:underline font-medium">Kembali ke login</a>
      </p>
    </div>

    {{-- STEP 2: OTP --}}
    <div id="step-otp" class="space-y-5 hidden">
      <div class="relative">
        <label class="block text-sm font-medium mb-2">Kode OTP</label>
        <input type="text" id="otp" maxlength="6" placeholder="Masukkan Kode OTP" class="{{ $inputClass }}">
        <i class="fa-solid fa-key absolute right-3 top-10 text-gray-500"></i>
      </div>

      <button id="btn-verify-otp" onclick="verifyOtp()"
        class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg duration-300">
        Verifikasi OTP
      </button>

      <p id="otp-info" class="text-sm text-center mt-2 text-black"></p>
      <p id="otp-timer" class="text-sm text-center mt-1 text-black font-medium"></p>
    </div>

    {{-- STEP 3: RESET PASSWORD --}}
    <div id="step-password" class="space-y-5 hidden">
      <div class="relative">
        <label class="block text-sm font-medium mb-2">Kata Sandi Baru</label>
        <input type="password" id="password" placeholder="Masukkan Kata Sandi Baru"
               class="{{ $inputClass }}" minlength="6">
        <i class="fa-solid fa-eye-slash absolute right-3 top-11 text-gray-500 cursor-pointer"
           onclick="togglePassword('password', this)"></i>
      </div>

      <div class="relative">
        <label class="block text-sm font-medium mb-2">Konfirmasi Kata Sandi</label>
        <input type="password" id="password_confirmation" placeholder="Konfirmasi Kata Sandi"
               class="{{ $inputClass }}">
        <i class="fa-solid fa-eye-slash absolute right-3 top-11 text-gray-500 cursor-pointer"
           onclick="togglePassword('password_confirmation', this)"></i>
      </div>

      <button onclick="updatePassword()"
        class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg duration-300">
        Simpan Perubahan
      </button>
    </div>

    <div id="msg" class="text-center text-sm mt-3 font-medium"></div>
  </div>

  {{-- ========================= --}}
  {{-- JAVASCRIPT API HANDLER   --}}
  {{-- ========================= --}}
  <script>

    function msg(text, ok) {
      const el = document.getElementById('msg');
      el.innerText = text;
      el.style.color = ok ? 'green' : 'red';
    }

    function show(id, state) {
      document.getElementById(id).classList.toggle('hidden', !state);
    }

    function togglePassword(id, icon) {
      const el = document.getElementById(id);
      const hidden = el.type === 'password';
      el.type = hidden ? 'text' : 'password';
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    }

    let otpTimerInterval = null;

    function startOtpCountdown(seconds, email) {
      clearInterval(otpTimerInterval);
      const infoEl = document.getElementById('otp-info');
      const timerEl = document.getElementById('otp-timer');
      const verifyBtn = document.getElementById('btn-verify-otp');

      infoEl.innerText = `OTP terkirim ke ${email}. Akan kedaluwarsa dalam 05:00.`;
      verifyBtn.disabled = false;

      let remaining = seconds;
      function fmt(s) {
        const m = Math.floor(s / 60).toString().padStart(2, '0');
        const sec = (s % 60).toString().padStart(2, '0');
        return `${m}:${sec}`;
      }

      timerEl.innerText = fmt(remaining);
      otpTimerInterval = setInterval(() => {
        remaining--;
        if (remaining <= 0) {
          clearInterval(otpTimerInterval);
          timerEl.innerText = 'OTP telah kedaluwarsa';
          infoEl.innerText = `OTP untuk ${email} sudah kedaluwarsa. Silakan kirim ulang OTP.`;
          verifyBtn.disabled = true;
        } else {
          timerEl.innerText = fmt(remaining);
        }
      }, 1000);
    }

    async function sendOtp() {
      const email = document.getElementById('email').value;
      const btn = document.getElementById('btn-send-otp');
      if (btn) btn.disabled = true;

      const r = await fetch('/api/password/send-otp', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
      });

      const j = await r.json();
      msg(j.message, r.ok);

      if (r.ok) {
        show('step-email', false);
        show('step-otp', true);
        // mulai countdown 5 menit = 300 detik
        startOtpCountdown(300, email);
      } else {
        if (btn) btn.disabled = false;
      }
    }

    async function verifyOtp() {
      const email = document.getElementById('email').value;
      const otp = document.getElementById('otp').value;

      const r = await fetch('/api/password/verify-otp', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, otp })
      });

      const j = await r.json();
      msg(j.message, r.ok);

      if (r.ok) {
        // stop timer
        clearInterval(otpTimerInterval);
        show('step-otp', false);
        show('step-password', true);
      }
    }

    async function updatePassword() {
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const password_confirmation = document.getElementById('password_confirmation').value;

      const r = await fetch('/api/password/update', { // << perbaikan endpoint
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          email,
          password,
          password_confirmation
        })
      });

      const j = await r.json();
      msg(j.message, r.ok);

      if (r.ok) {
        window.location.href = "/login";
      }
    }

  </script>

</body>
</html>