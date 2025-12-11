@extends('layouts.landing')

@section('title', $ponpes->nama_ponpes . ' - Landing Page')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Carousel Section -->
    @if($carousels->isNotEmpty())
    <div id="carousel" class="relative">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($carousels as $carousel)
                <div class="swiper-slide relative">
                    <img src="{{ Storage::url($carousel->image) }}" 
                         alt="{{ $carousel->title }}" 
                         class="w-full h-96 object-cover">
                    @if($carousel->title || $carousel->subtitle)
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <div class="text-center text-white px-4">
                            @if($carousel->title)
                            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $carousel->title }}</h1>
                            @endif
                            @if($carousel->subtitle)
                            <p class="text-xl md:text-2xl">{{ $carousel->subtitle }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Add Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
    @endif

    <!-- About Section -->
    @if($founders->isNotEmpty() || $leaders->isNotEmpty())
    <div class="container mx-auto px-4 py-12">
        <!-- Founders Section -->
        @if($founders->isNotEmpty())
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center mb-8">Pendiri & Founder</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($founders as $founder)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($founder->image)
                    <img src="{{ Storage::url($founder->image) }}" 
                         alt="{{ $founder->title }}" 
                         class="w-full h-64 object-cover">
                    @endif
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $founder->title }}</h3>
                        <p class="text-gray-600 mb-3">{{ $founder->position }}</p>
                        <p class="text-gray-700">{{ Str::limit($founder->description, 150) }}</p>
                        @if($founder->url)
                        <a href="{{ $founder->url }}" target="_blank" 
                           class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                            Lihat Profil →
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Leaders Section -->
        @if($leaders->isNotEmpty())
        <div>
            <h2 class="text-3xl font-bold text-center mb-8">Struktur Pengurus</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($leaders as $leader)
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    @if($leader->image)
                    <img src="{{ Storage::url($leader->image) }}" 
                         alt="{{ $leader->title }}" 
                         class="w-32 h-32 object-cover rounded-full mx-auto mb-4">
                    @endif
                    <h3 class="text-lg font-bold mb-1">{{ $leader->title }}</h3>
                    <p class="text-gray-600 mb-3">{{ $leader->position }}</p>
                    <p class="text-sm text-gray-700">{{ Str::limit($leader->description, 100) }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Section Titles -->
    @if($sectionTitles->isNotEmpty())
    <div class="container mx-auto px-4 py-12">
        @foreach($sectionTitles as $section)
        <div class="mb-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">{{ $section->title }}</h2>
                @if($section->subtitle)
                <p class="text-gray-600 mt-2">{{ $section->subtitle }}</p>
                @endif
                <div class="w-24 h-1 bg-blue-600 mx-auto mt-4"></div>
            </div>
            
            @if($section->description)
            <div class="prose max-w-3xl mx-auto">
                {!! nl2br(e($section->description)) !!}
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Footer Section -->
    @if($footerLinks->isNotEmpty())
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ $ponpes->nama_ponpes }}</h3>
                    <p class="text-gray-300">{{ $ponpes->alamat ?? 'Alamat belum tersedia' }}</p>
                    @if($ponpes->telepon)
                    <p class="text-gray-300 mt-2">📞 {{ $ponpes->telepon }}</p>
                    @endif
                    @if($ponpes->email)
                    <p class="text-gray-300 mt-2">✉️ {{ $ponpes->email }}</p>
                    @endif
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        @foreach($footerLinks->where('position', 'quick_link') as $link)
                        <li>
                            <a href="{{ $link->url }}" class="text-gray-300 hover:text-white">
                                {{ $link->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Social Media -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        @foreach($footerLinks->where('position', 'social') as $link)
                        <a href="{{ $link->url }}" 
                           class="text-gray-300 hover:text-white p-2 bg-gray-700 rounded-lg"
                           target="_blank"
                           title="{{ $link->title }}">
                            @php
                            $icon = $link->icon ?? 'link';
                            @endphp
                            @if($icon == 'instagram')
                            📸
                            @elseif($icon == 'facebook')
                            📘
                            @elseif($icon == 'twitter')
                            🐦
                            @elseif($icon == 'youtube')
                            📺
                            @elseif($icon == 'whatsapp')
                            💬
                            @else
                            🔗
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ $ponpes->nama_ponpes }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<style>
.swiper-container {
    width: 100%;
    height: 400px;
}
.swiper-slide {
    text-align: center;
    font-size: 18px;
    background: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
}
.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper Carousel
    @if($carousels->isNotEmpty())
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });
    @endif
});
</script>
@endpush