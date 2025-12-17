{{-- 
Dikerjakan Oleh: Titho (3312401071) Front-End
               : Muhammad Rizky (3312401071) Back-End
Dikerjakan Pada: 10 October 2025 (Front-End) & 16 October 2025 (Back-End) 
Deskripsi      : Halaman Lupa Kata Sandi dengan API
--}}

@extends('index')

@section('title', 'Lupa Kata Sandi')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8">
    <div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Lupa Kata Sandi
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Masukkan email Anda untuk mereset kata sandi
      </p>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
      <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
      </svg>
      {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
      <ul class="list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <!-- Email Form (Default) -->
    @if(!session('otp_sent') && !session('otp_verified'))
    <form id="otpForm" class="mt-8 space-y-6" action="{{ route('password.otp.send') }}" method="POST">
      @csrf

      <div>
        <label for="email" class="sr-only">Email address</label>
        <input id="email" name="email" type="email" autocomplete="email" required
          class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
          placeholder="Masukkan email Anda" value="{{ old('email') }}">
      </div>

      <div>
        <button id="btnOtpSend" type="submit"
          class="group relative w-full flex justify-center items-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition duration-200">

          <span id="btnOtpText">Kirim Kode OTP</span>

          <!-- Spinner (PREFERENSI KAMU) -->
          <svg id="btnSpinner" class="animate-spin h-4 w-4 ml-2 hidden" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10"
              stroke="currentColor" stroke-width="4"></circle>
            <circle class="opacity-75" cx="12" cy="12" r="10"
              stroke="currentColor" stroke-width="4" stroke-dasharray="60" stroke-dashoffset="20"></circle>
          </svg>

        </button>
      </div>
    </form>
    @endif

    <!-- OTP Form (Setelah email dikirim) -->
    @if(session('otp_sent') && !session('otp_verified'))
    <form class="mt-8 space-y-6" action="{{ route('password.otp.verify') }}" method="POST">
      @csrf
      <div>
        <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">
          Kode OTP
        </label>
        <input id="otp" name="otp" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" required
          class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm text-center text-lg font-mono"
          placeholder="000000" autocomplete="one-time-code">
        <p class="text-xs text-gray-500 mt-2 text-center">
          Masukkan 6 digit kode OTP yang dikirim ke {{ session('email') }}
        </p>
      </div>

      <div class="flex space-x-3">
        <button type="submit"
          class="flex-1 py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          Verifikasi OTP
        </button>
        <button type="button" onclick="resendOtp()"
          class="flex-1 py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          Kirim Ulang
        </button>
      </div>
    </form>
    @endif

    <!-- Password Form (Setelah OTP terverifikasi) -->
    @if(session('otp_verified'))
    <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST">
      @csrf
      <div>
        <label for="password" class="sr-only">Kata Sandi Baru</label>
        <input id="password" name="password" type="password" autocomplete="new-password" required
          class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
          placeholder="Kata sandi baru (minimal 6 karakter)">
      </div>

      <div>
        <label for="password_confirmation" class="sr-only">Konfirmasi Kata Sandi</label>
        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
          class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
          placeholder="Konfirmasi kata sandi">
      </div>

      <div>
        <button type="submit"
          class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          Reset Kata Sandi
        </button>
      </div>
    </form>
    @endif

    <!-- Back to Login -->
    <div class="text-center">
      <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
        Kembali ke halaman login
      </a>
    </div>
  </div>
</div>

<script>
  document.getElementById('otpForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnOtpSend');

    // nonaktifkan tombol
    btn.disabled = true;
    btn.classList.add('bg-blue-400');

    // ubah teks
    document.getElementById('btnOtpText').innerText = 'Mengirim...';

    // tampilkan spinner
    document.getElementById('btnSpinner').classList.remove('hidden');
  });

  function resendOtp() {
    if (confirm('Kirim ulang kode OTP?')) {
      fetch('{{ route("password.otp.resend") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      }).then(response => {
        if (response.ok) {
          alert('Kode OTP telah dikirim ulang');
          location.reload();
        } else {
          alert('Gagal mengirim ulang OTP');
        }
      });
    }
  }
</script>
@endsection