
@extends('index')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Kata Sandi</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-[#344E41]">

  <div class="bg-[#F8FFF8] w-full max-w-md rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center mb-12">Lupa kata sandi</h2>

    <form action="#" method="POST">
      <!-- Email -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-2">Email</label>
        <input type="email" placeholder="Masukkan Email" 
          class="w-full p-3 rounded-lg border border-[#C3C8C5] bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <!-- Kata Sandi Baru -->
      <div class="mb-3">
        <label class="block text-sm font-medium mb-2">Kata Sandi Baru</label>
        <input type="password" placeholder="Masukkan Kata Sandi Baru" 
          class="w-full p-3 rounded-lg border border-[#C3C8C5] bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <!-- Konfirmasi Kata Sandi -->
      <div class="mb-12">
        <label class="block text-sm font-medium mb-2">Konfirmasi Kata Sandi</label>
        <input type="password" placeholder="Konfirmasi Kata Sandi" 
          class="w-full p-3 rounded-lg border border-[#C3C8C5] bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <!-- Tombol Simpan -->
      <div class="flex justify-center">
        <button type="submit" 
          class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg transition duration-300">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>

</body>
</html>
