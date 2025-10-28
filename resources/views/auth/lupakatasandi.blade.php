{{-- 
Dikerjakan Oleh: Titho (3312401071) Front-End
               : Muhammad Rizky (3312401071) Back-End
Dikerjakan Pada: 10 October 2025 (Front-End) & 16 October 2025 (Back-End) 
Deskripsi      : Membuat halaman Lupa Kata Sandi dengan form yang terstruktur dan menarik menggunakan Tailwind CSS.
                 Form ini mencakup input untuk email, OTP, dan pengaturan ulang kata sandi.
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
    .input-custom {
      font-family: 'Poppins';
      font-size: 15px;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center relative">

  <!-- Background gambar -->
  <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('asset/background masjid .png') }}');"></div>

  <!-- Overlay blur -->
  <div class="absolute inset-0 backdrop-blur-sm bg-black/20"></div>

  <div class="relative z-10 bg-[#F8FFF8]/95 w-full max-w-md rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center mb-10">Lupa Kata Sandi</h2>

    @php
      $inputClass = 'w-full h-[45px] pl-3 pr-10 text-sm placeholder:text-sm bg-gray-100 
      border border-[#C3C8C5] rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500';
    @endphp

    {{-- Langkah 1: Masukkan Email --}}
    @if(!session('otp_sent') && !session('otp_verified'))
      <form action="{{ route('password.sendOtp') }}" method="POST" class="space-y-5">
        @csrf
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Email</label>
          <input type="email" name="email" placeholder="Masukkan Email" required class="{{ $inputClass }}">
          <i class="fa-solid fa-envelope absolute right-3 top-10 text-gray-500"></i>
        </div>
        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg transition duration-300">
          Kirim OTP
        </button>
        <p class="text-sm text-center mt-3 text-black">
          <a href="{{ route('login') }}" class="text-black hover:underline font-medium">Kembali ke login</a>
        </p>
      </form>
    @endif

    {{-- Langkah 2: Masukkan OTP --}}
    @if(session('otp_sent') && !session('otp_verified'))
      <form action="{{ route('password.verifyOtp') }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="email" value="{{ session('email') }}">
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Kode OTP</label>
          <input type="text" name="otp" placeholder="Masukkan Kode OTP" maxlength="6" required class="{{ $inputClass }}">
          <i class="fa-solid fa-key absolute right-3 top-10 text-gray-500"></i>
        </div>
        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg transition duration-300">
          Verifikasi OTP
        </button>
      </form>
    @endif

    {{-- Langkah 3: Ubah Password --}}
    @if(session('otp_verified'))
      <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="email" value="{{ session('email') }}">

        <div class="relative ">
          <label class="block text-sm font-medium mb-2">Kata Sandi Baru</label>
          <input type="password" name="password" id="password" placeholder="Masukkan Kata Sandi Baru" required minlength="6" class="{{ $inputClass }}">
          <i class="fa-solid fa-eye-slash absolute right-3 top-11 text-gray-500 cursor-pointer" onclick="togglePassword('password', this)"></i>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Konfirmasi Kata Sandi</label>
          <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Kata Sandi" required class="{{ $inputClass }}">
          <i class="fa-solid fa-eye-slash absolute right-3 top-11 text-gray-500 cursor-pointer" onclick="togglePassword('password_confirmation', this)"></i>
        </div>

        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg transition duration-300">
          Simpan Perubahan
        </button>
      </form>

      <script>
  function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    const isHidden = input.type === 'password';
    
    if (isHidden) {
      input.type = 'text';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    }
  }
</script>
    @endif
  </div>

</body>
</html>
