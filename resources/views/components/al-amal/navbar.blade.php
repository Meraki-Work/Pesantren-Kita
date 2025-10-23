<!-- 
 Nama file   : navbar.blade.php
 Deskripsi   : File ini berfungsi untuk menampilkan navigasi utama (navbar) pada bagian atas website 
               Pondok Pesantren Al Amal Batam.
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 Oktober 2025
--> 

<header class="fixed top-0 left-0 w-full bg-[#2e4f45] text-white shadow-md z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-8 py-2">
        <!-- Logo -->
        <div class="flex items-center">
            <img src="{{ asset('asset/logo_al-amal.png') }}" 
                 alt="Logo Al-amal" 
                 class="w-48 h-auto" />
        </div>

        <!-- Menu -->
        <nav class="absolute left-1/2 transform -translate-x-1/2 flex gap-12 text-sm md:text-base font-medium tracking-wide">
            <a href="#" class="hover:text-[#a0f0c5] transition">Home</a>
            <a href="#about" class="hover:text-[#a0f0c5] transition">About</a>
            <a href="#contact" class="hover:text-[#a0f0c5] transition">Contact</a>
        </nav>
    </div>
</header>
