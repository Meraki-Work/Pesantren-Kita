<!-- 
 Nama file   : card_galeri.blade.php
 Deskripsi   : File ini berfungsi sebagai komponen tampilan galeri foto pada website pesantren.
               Tujuannya adalah untuk menampilkan kumpulan gambar (foto kegiatan, fasilitas, atau lingkungan pesantren) 
               secara rapi dan responsif
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 Oktober 2025
--> 

<section class="py-20 bg-white-100">
    <div class="container mx-auto px-6 md:px-12">
        {{-- Card utama --}}
        <div class="bg-green-100 rounded-xl shadow-md p-10">
            <h2 class="text-center text-xl font-semibold text-gray-800 mb-10">Galeri Foto</h2>

            {{-- Grid Galeri --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">

                {{-- Galeri 1 --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden w-full h-48 hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('asset/imgmasjid.png') }}" 
                         class="w-full h-full object-cover" 
                         alt="Galeri Foto 1"
                         onerror="this.style.display='none'; this.parentNode.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-300 text-sm\'>Foto 1</div>';">
                </div>

                {{-- Galeri 2 --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden w-full h-48 hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('asset/imgmasjid.png') }}" 
                         class="w-full h-full object-cover" 
                         alt="Galeri Foto 2"
                         onerror="this.style.display='none'; this.parentNode.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-300 text-sm\'>Foto 2</div>';">
                </div>

                {{-- Galeri 3 --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden w-full h-48 hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('asset/imgmasjid.png') }}" 
                         class="w-full h-full object-cover" 
                         alt="Galeri Foto 3"
                         onerror="this.style.display='none'; this.parentNode.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-300 text-sm\'>Foto 3</div>';">
                </div>

                {{-- Galeri 4 --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden w-full h-48 hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('asset/imgmasjid.png') }}" 
                         class="w-full h-full object-cover" 
                         alt="Galeri Foto 4"
                         onerror="this.style.display='none'; this.parentNode.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-300 text-sm\'>Foto 4</div>';">
                </div>

                {{-- Galeri 5 --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden w-full h-48 hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('asset/imgmasjid.png') }}" 
                         class="w-full h-full object-cover" 
                         alt="Galeri Foto 5"
                         onerror="this.style.display='none'; this.parentNode.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-300 text-sm\'>Foto 5</div>';">
                </div>

                {{-- Galeri 6 --}}
                <div class="bg-white rounded-lg shadow-sm overflow-hidden w-full h-48 hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('asset/imgmasjid.png') }}" 
                         class="w-full h-full object-cover" 
                         alt="Galeri Foto 6"
                         onerror="this.style.display='none'; this.parentNode.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-300 text-sm\'>Foto 6</div>';">
                </div>

            </div>
        </div>
    </div>
</section>
