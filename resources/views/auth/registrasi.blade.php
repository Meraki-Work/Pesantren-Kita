@extends('index')
<body class="min-h-screen flex items-center justify-center bg-[#344E41]">

  <div class="bg-[#F8FFF8] w-full max-w-4xl rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-center mb-6">Registrasi</h2>

    <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Kolom kiri -->
      <div>
        <label class="block text-sm font-medium mb-1">Nama Pengguna</label>
        <input type="text" placeholder="Masukkan Nama Lengkap" 
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">

        <label class="block text-sm font-medium mt-4 mb-1">Email</label>
        <input type="email" placeholder="Masukkan Email" 
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">

        <label class="block text-sm font-medium mt-4 mb-1">Nomor Telepon</label>
        <input type="text" placeholder="Masukkan Nomor Telepon" 
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">

        <label class="block text-sm font-medium mt-4 mb-1">Alamat</label>
        <input type="text" placeholder="Masukkan Alamat" 
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
      </div>

      <!-- Kolom kanan -->
      <div>
        <label class="block text-sm font-medium mb-1">Nama Pondok Pesantren</label>
        <input type="text" placeholder="Masukkan Nama Pondok Pesantren" 
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">

        <label class="block text-sm font-medium mt-4 mb-1">Tahun Masuk</label>
        <select class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
          <option>Pilih Tahun Masuk</option>
          <option>2023</option>
          <option>2024</option>
          <option>2025</option>
        </select>

        <label class="block text-sm font-medium mt-4 mb-1">Kata Sandi</label>
        <input type="password" placeholder="Masukkan Kata Sandi" 
          class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">

        <label class="block text-sm font-medium mt-4 mb-1">Role</label>
        <select class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
          <option>Pilih Role</option>
          <option>Santri</option>
          <option>Pengajar</option>
          <option>Admin</option>
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