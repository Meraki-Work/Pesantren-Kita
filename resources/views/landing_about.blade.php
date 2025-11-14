{{-- 
    Nama file   : landing_about.blade.php
    Deskripsi   : Menampilkan halaman “About” yang berisi daftar pondok pesantren pengguna website dalam bentuk komponen kartu.
    Dibuat oleh : Zahrah Nazihah Ginting - NIM: 3312401077
    Tanggal     : 13 Oktober 2025
--}}

@extends('layouts.landing')

@section('title', 'Tentang Kami - PesantrenKita')

@section('content')
<!-- Hero Section -->
<section class="bg-white py-16">
    <div class="container mx-auto flex flex-col md:flex-row items-center justify-between gap-8 px-6">
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                Pesantren Yang Telah Bergabung Bersama Kami
            </h1>
            <p class="text-gray-600 mb-6">
                Kami berkomitmen mendukung digitalisasi pesantren di seluruh Indonesia
            </p>
            <a href="#kontak" class="bg-green-700 text-white px-5 py-3 rounded-lg hover:bg-green-800 transition">
                Ingin bergabung? Hubungi Kami
            </a>
        </div>
        <div class="flex-1">
          <img 
    src="{{ asset('asset/carousel_utama.png') }}" 
    class="w-[600px] h-[300px] object-cover rounded-2xl mx-auto md:mx-0" 
    alt="">
        </div>
    </div>
</section>

<!-- Pesantren Tergabung Section -->
<section class="bg-gray-50 py-16">
    <div class="container mx-auto px-6">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-10">
            Pesantren Tergabung
        </h2>

        <!-- Grid 2x2 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @for ($i = 0; $i < 4; $i++)
            <div class="bg-white shadow-md rounded-xl overflow-hidden hover:shadow-lg transition">
               <img 
    src="{{ asset('asset/foto_masjid2.png') }}" 
    alt="Pesantren Al Amal Batam" 
    class="w-250 h-[400px] object-cover rounded-t-xl"
/>

                <div class="p-5">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">
                        Pondok Pesantren Al Amal Batam
                    </h3>
                    <p class="text-sm text-gray-600 mb-3">
                        Perumahan Kejaksaan No.12/4-24 Batam, Sumatera
                    </p>
                    <a href="#" 
                       class="inline-block text-sm bg-green-700 text-white px-4 py-1.5 rounded-md hover:bg-green-800 transition">
                        Lihat Profil
                    </a>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>
@endsection