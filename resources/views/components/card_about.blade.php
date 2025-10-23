{{-- 
    Nama file   : card_about.blade.php
    Deskripsi   : Komponen tampilan (UI component) untuk menampilkan informasi singkat tentang pondok pesantren dalam bentuk kartu. 
                    Komponen ini menampilkan gambar pondok di sisi kiri dan detail seperti nama, alamat, kota, dan provinsi di sisi kanan.
    Dibuat oleh : Zahrah Nazihah Ginting - NIM: 3312401077
    Tanggal     : 13 Oktober 2025
--}}

{{-- resources/views/components/card_about.blade.php --}}
@props([
    'nama' => 'Nama Pondok',
    'lokasiLabel' => 'Lokasi :',
    'alamat' => 'Perumahan Kejaksaan No.11',
    'kota' => '29424 Batam',
    'provinsi' => 'Sumatera',
    'gambar' => '/images/pondok1.jpg',
    'link' => null,
])

<div class="bg-white rounded-2xl shadow-md overflow-hidden flex items-stretch max-w-full">
    <div class="relative w-1/2 min-h-[220px] md:min-h-[180px] lg:min-h-[200px]">
        <a href="{{ $link }}" target="_blank"> {{-- Klik gambar buka tab baru --}}
            <img src="{{ $gambar }}" alt="{{ $nama }}" class="absolute inset-0 w-full h-full object-cover rounded-l-2xl">
            <div class="absolute inset-0 rounded-l-2xl" style="background: linear-gradient(180deg, rgba(0,0,0,0.18) 0%, rgba(0,0,0,0.18) 100%);"></div>
        </a>
        <div class="absolute right-3 bottom-3 bg-white/90 rounded-full p-1 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </div>

    <div class="w-1/2 p-6 flex flex-col justify-center">
        <h3 class="text-[15px] md:text-[16px] lg:text-[17px] font-semibold text-[#1f4338] leading-tight">
            {{ $nama }}
        </h3>

        <p class="text-xs text-gray-400 mt-3">{{ $lokasiLabel }}</p>

        <div class="mt-2 text-sm space-y-1">
            <a href="#" class="block text-[13px] text-[#1b6b60] underline hover:text-[#174f46]">{{ $alamat }}</a>
            <a href="#" class="block text-[13px] text-[#1b6b60] underline hover:text-[#174f46]">{{ $kota }}</a>
            <a href="#" class="block text-[13px] text-[#1b6b60] underline hover:text-[#174f46]">{{ $provinsi }}</a>
        </div>
    </div>
</div>
