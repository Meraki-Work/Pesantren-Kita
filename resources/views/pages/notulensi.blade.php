@extends('index')

@section('title', 'Notulensi-PesantrenKita')

@section('content')

<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-4">
        {{ $slot ?? '' }}
        <div class="swiper">
            <div class="swiper-wrapper">
                @foreach($cards as $card)
                <div class="swiper-slide box-bg p-4 max-w-[48%] max-h-32">
                    <div class="flex gap-4">
                        {{-- Kolom kiri --}}
                        <div class="w-1/2">
                            <h2 class="font-semibold mb-1">{{ $card['kategori'] }}</h2>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>Pimpinan: {{ $card['jumlah'] }}</p>
                                <p>Tanggal: {{ $card['jumlah'] }}</p>
                                <p>Lokasi: {{ $card['jumlah'] }}</p>
                            </div>
                        </div>

                        {{-- Kolom kanan --}}
                        <div class="relative w-1/2 border-l border-gray-200 pl-3">
                            <p class="text-gray-700 text-sm line-clamp-5 overflow-hidden">
                                <br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            </p>
                            {{-- efek fade --}}
                            <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="swiper-button-prev">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f">
                    <path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z" />
                </svg>
            </div>
            <div class="swiper-button-next">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f">
                    <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z" />
                </svg>
            </div>
        </div>
        <x-dynamic-table :columns="$columns" :rows="$rows" />
    </main>
</div>
@endsection