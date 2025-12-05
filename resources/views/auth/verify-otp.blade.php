@extends('index')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - PesantrenKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-center mb-6">Verifikasi OTP</h2>

        {{-- ✅ TAMPILKAN OTP JIKA EMAIL GAGAL --}}
        @if(session('otp_display'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-4">
                <div class="font-bold mb-2">⚠️ Email tidak terkirim!</div>
                <p class="mb-2">Gunakan kode OTP berikut untuk verifikasi:</p>
                <div class="text-center">
                    <span class="text-2xl font-bold text-red-600">{{ session('otp_display') }}</span>
                </div>
                <p class="text-sm mt-2">Kode berlaku 10 menit</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('verify.otp') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kode OTP</label>
                <input type="text" 
                       name="otp" 
                       maxlength="6" 
                       required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-center text-lg font-mono"
                       placeholder="000000"
                       autocomplete="off">
                <p class="text-xs text-gray-500 mt-1">Masukkan 6 digit kode OTP</p>
            </div>

            <button type="submit" 
                    class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md font-semibold">
                Verifikasi
            </button>
        </form>

        {{-- Form untuk kirim ulang OTP --}}
        <form action="{{ route('resend.otp') }}" method="POST" class="mt-4">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" 
                    class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">
                Kirim Ulang OTP
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            <a href="{{ route('registrasi.index') }}" class="text-green-600 hover:underline">
                Kembali ke Registrasi
            </a>
        </p>
    </div>

    {{-- Auto focus dan input validation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto focus ke input OTP
            document.querySelector('input[name="otp"]').focus();
            
            // Auto numeric input
            document.querySelector('input[name="otp"]').addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</body>
</html>