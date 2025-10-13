@extends('layouts.app')

@section('title', 'Landing Page')

@section('content')
    {{-- Carousel Section --}}
    <x-carousel />

    {{-- Tentang Kami --}}
    <section id="about" class="bg-[#344E41] text-white text-center py-16 px-4">
        <h2 class="text-2xl md:text-3xl font-semibold mb-4">Apa Itu PesantrenKita?</h2>
        <p class="max-w-3xl mx-auto leading-relaxed text-gray-200">
            PesantrenKita adalah aplikasi manajemen berbasis web yang membantu pondok pesantren dalam mengelola data santri, keuangan, dan kegiatan secara terpusat.
        </p>
    </section>

    {{-- Card Utama (Fitur + Kelebihan) --}}
    <x-card_utama />

@endsection
