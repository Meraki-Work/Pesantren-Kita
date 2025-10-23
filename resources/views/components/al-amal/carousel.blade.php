<!-- 
 Nama file   : carousel.blade.php
 Deskripsi   : File ini berfungsi untuk menampilkan komponen carousel (slider gambar utama) di halaman depan website 
               Pondok Pesantren Al Amal Batam.
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 Oktober 2025
--> 

<div class="relative w-full h-[80vh] overflow-hidden">

    {{-- Slide 1 --}}
    <div class="carousel-slide absolute inset-0 opacity-100 transition-opacity duration-700 ease-in-out">
        <img src="{{ asset('asset/carousel_utama.png') }}" 
             class="w-full h-full object-cover brightness-75" 
             alt="Slide 1">
        <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold">Pondok Pesantren Al Amal Batam</h1>
            <p class="italic text-lg mt-2">Selamat Datang di Website Pondok Pesantren Al Amal Batam</p>
        </div>
    </div>

    {{-- Slide 2 --}}
    <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
        <img src="{{ asset('asset/carousel_utama.png') }}" 
             class="w-full h-full object-cover brightness-75" 
             alt="Slide 2">
        <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold">Pondok Pesantren Al Amal Batam</h1>
            <p class="italic text-lg mt-2">Membentuk Generasi Qurani dan Berakhlakul Karimah</p>
        </div>
    </div>

    {{-- Slide 3 --}}
    <div class="carousel-slide absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
        <img src="{{ asset('asset/carousel_utama.png') }}" 
             class="w-full h-full object-cover brightness-75" 
             alt="Slide 3">
        <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold">Pondok Pesantren Al Amal Batam</h1>
            <p class="italic text-lg mt-2">Tempat Belajar, Beribadah, dan Berkarya</p>
        </div>
    </div>

    {{-- Dots Navigation --}}
    <div class="absolute bottom-5 left-0 right-0 flex justify-center space-x-3">
        <button class="carousel-dot w-3 h-3 rounded-full bg-white transition-all duration-300"></button>
        <button class="carousel-dot w-3 h-3 rounded-full bg-gray-400 transition-all duration-300"></button>
        <button class="carousel-dot w-3 h-3 rounded-full bg-gray-400 transition-all duration-300"></button>
    </div>
</div>
