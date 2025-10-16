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
  <title>Registrasi</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-[#344E41]">

  <div class="bg-[#F8FFF8] w-full max-w-4xl rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center mb-6">Registrasi Pengguna</h2>

    @if ($errors->any())
        <div>{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('register.store') }}">
      @csrf

      <!-- Kolom kiri -->
      <div>
        <label class="block text-sm font-medium mb-1">Nama Pengguna</label>
        <input type="text" name="username" placeholder="Masukkan Nama Pengguna"
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">

        <label class="block text-sm font-medium mt-4 mb-1">Email</label>
        <input type="email" name="email" placeholder="Masukkan Email"
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">

        <label class="block text-sm font-medium mt-4 mb-1">Kata Sandi</label>
        <input type="password" name="password" placeholder="Masukkan Kata Sandi"
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <!-- Kolom kanan -->
      <div>
        <label class="block text-sm font-medium mt-4 mb-1">Nama Pondok Pesantren</label>
        <select name="ponpes_id"
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
          <option value="">Pilih Pondok Pesantren</option>
          @foreach ($ponpes as $p)
              <option value="{{ $p->id_ponpes }}">{{ $p->nama }}</option>
          @endforeach
        </select>

        <label class="block text-sm font-medium mt-4 mb-1">Role</label>
        <select name="role"
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
          <option value="">Pilih Role</option>
          <option value="Admin">Admin</option>
          <option value="Pengajar">Pengajar</option>
        </select>
      </div>

      <!-- Tombol Registrasi -->
      <div class="md:col-span-2 flex justify-center mt-6">
        <button type="submit"
          class="w-full md:w-[300px] bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition duration-300">
          Registrasi
        </button>
      </div>
    </form>
  </div>

</body>
</html>
