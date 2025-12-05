<!-- 
 Nama file   : carousel.blade.php
 Deskripsi   : File ini berfungsi untuk menampilkan komponen carousel (slider gambar utama) di halaman depan website 
               Pondok Pesantren Al Amal Batam.
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 Oktober 2025
--> 
@props(['carousels'])

<div class="relative w-full h-[80vh] overflow-hidden">

    {{-- Slides --}}
    @foreach ($carousels as $index => $item)
        <div class="carousel-slide absolute inset-0 transition-opacity duration-700 ease-in-out
            {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">

            {{-- Gambar --}}
            <img src="{{ asset('uploads/carousel/' . $item->image) }}"
                class="w-full h-full object-cover brightness-75">

            {{-- Text Overlay --}}
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white px-4">
                <h1 class="text-4xl md:text-5xl font-bold">{{ $item->title }}</h1>
                <p class="italic text-lg mt-2">{{ $item->subtitle }}</p>
            </div>
        </div>
    @endforeach

    {{-- Dots --}}
    <div class="absolute bottom-5 left-0 right-0 flex justify-center space-x-3">
        @foreach ($carousels as $index => $item)
            <button class="carousel-dot w-3 h-3 rounded-full transition-all duration-300
                {{ $index === 0 ? 'bg-white' : 'bg-gray-400' }}">
            </button>
        @endforeach
    </div>
</div>

{{-- Script Carousel --}}
<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.carousel-dot');

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('opacity-100', i === index);
            slide.classList.toggle('opacity-0', i !== index);
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-white', i === index);
            dot.classList.toggle('bg-gray-400', i !== index);
        });

        currentSlide = index;
    }

    // Auto slide
    setInterval(() => {
        let next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }, 3500);

    // Klik dot
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });
</script>

