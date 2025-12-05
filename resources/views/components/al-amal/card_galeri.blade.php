<!-- 
 Nama file   : card_galeri.blade.php
 Deskripsi   : File ini berfungsi sebagai komponen tampilan galeri foto pada website pesantren.
               Tujuannya adalah untuk menampilkan kumpulan gambar (foto kegiatan, fasilitas, atau lingkungan pesantren) 
               secara rapi dan responsif
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 Oktober 2025
--> 
@props(['galleries' => null])

@php
$galleries = $galleries ?? collect([]);
@endphp

<section id="galeri" class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">

        <h2 class="text-center text-2xl font-semibold text-gray-800 mb-10">
            Galeri Foto
        </h2>

        <div class="bg-green-100 rounded-xl shadow-md p-10">

            {{-- Grid Galeri --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">

                @forelse ($galleries as $item)
                    <div class="bg-white rounded-xl shadow overflow-hidden 
                                w-full h-52 group cursor-pointer 
                                hover:shadow-lg transition-all duration-300">

                        <img src="{{ asset('uploads/gallery/' . $item->image) }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             alt="Foto Galeri"
                             onerror="this.style.display='none';
                                      this.parentNode.innerHTML='<div class=\'flex items-center justify-center h-full text-gray-400 text-sm\'>Foto tidak tersedia</div>';">
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-3">
                        Belum ada foto galeri.
                    </p>
                @endforelse

            </div>

        </div>

    </div>
</section>
