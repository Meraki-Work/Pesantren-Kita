<!-- 
 Nama file   : card_about.blade.php
 Deskripsi   : File ini berfungsi untuk menampilkan komponen card about (tentang kami) di halaman depan website 
               Pondok Pesantren Al Amal Batam.
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 Oktober 2025
--> 
@props(['abouts'])

<section class="py-16 bg-white">
  <div class="max-w-6xl mx-auto px-6 md:px-12 space-y-20">

    <!-- Blok Founder -->
    <div class="grid md:grid-cols-2 gap-10 items-start">
      <div>
        <span class="inline-block bg-green-700 text-white font-semibold px-4 py-1 rounded-full mb-3">
          Pondok Pesantren Al Amal Batam
        </span>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $abouts->founder_name }}</h2>
        <p class="text-gray-500 mb-6">{{ $abouts->founder_position }}</p>
        <p class="text-gray-700 leading-relaxed">
          {{ $abouts->founder_description }}
        </p>
      </div>

      <div>
        <img src="{{ asset('uploads/about/' . $abouts->founder_image) }}"
             class="rounded-lg shadow-lg w-full h-[320px] object-cover">
      </div>
    </div>

    <!-- Blok Leader -->
    <div class="grid md:grid-cols-2 gap-10 items-start">
      <div>
        <img src="{{ asset('uploads/about/' . $abouts->leader_image) }}"
             class="rounded-lg shadow-lg w-full h-[320px] object-cover">
      </div>

      <div>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $abouts->leader_name }}</h2>
        <p class="text-gray-500 mb-6">{{ $abouts->leader_position }}</p>
        <p class="text-gray-700 leading-relaxed">
          {{ $abouts->leader_description }}
        </p>
      </div>
    </div>

  </div>
</section>
