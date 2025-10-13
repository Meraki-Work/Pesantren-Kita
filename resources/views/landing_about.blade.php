{{-- 
    Nama file   : landing_about.blade.php
    Deskripsi   : Menampilkan halaman “About” yang berisi daftar pondok pesantren pengguna website dalam bentuk komponen kartu.
    Dibuat oleh : Zahrah Nazihah Ginting - NIM: 3312401077
    Tanggal     : 13 Oktober 2025
--}}

@extends('layouts.landing')

@section('title', 'Tentang Kami - PesantrenKita')

@section('content')
<section class="py-16 px-8">
    <h2 class="text-2xl font-bold text-center text-[#1f4338] mb-12">
        Pengguna Website PesantrenKita
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <x-card_about
            nama="Pondok Pesantren Al Amal"
            alamat="Perumahan Kejaksaan No.11"
            kota="29424 Batam"
            provinsi="Sumatera"
            gambar="{{ asset('asset/carousel_utama.png') }}"
        />

        <x-card_about
            nama="Pondok Pesantren Nurul Falah"
            alamat="Jl. Melati No.5"
            kota="29432 Batam"
            provinsi="Sumatera"
            gambar="{{ asset('asset/carousel_utama.png') }}"
        />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-16">
        <x-card_about
            nama="Pondok Pesantren Al Amal"
            alamat="Perumahan Kejaksaan No.11"
            kota="29424 Batam"
            provinsi="Sumatera"
            gambar="{{ asset('asset/carousel_utama.png') }}"
        />

        <x-card_about
            nama="Pondok Pesantren Nurul Falah"
            alamat="Jl. Melati No.5"
            kota="29432 Batam"
            provinsi="Sumatera"
            gambar="{{ asset('asset/carousel_utama.png') }}"
        />
    </div>
</section>
@endsection
