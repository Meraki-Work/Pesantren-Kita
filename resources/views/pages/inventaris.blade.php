@extends('index')

@section('title', 'Inventaris')

@section('content')
<h1>Inventaris</h1>
@endsection

<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6">
        {{ $slot ?? '' }}

        {{-- Card Info --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
            @foreach ($cards as $card)
            <div class="flex flex-col rounded-xl box-bg p-4 hover:shadow-md transition">
                <div class="flex justify-between">
                    <label class="sm:text-lg md:text-xl lg:text-2xl">
                        {{ $card['kategori'] }}
                    </label>
                </div>
                <div class="my-2">
                    <span class="font-medium sm:text-lg md:text-xl lg:text-2xl">
                        Rp. {{ number_format($card['jumlah'], 0, ',', '.') }},00
                    </span>
                    <div class="grid">
                        <span class="text-mint text-[8px] sm:text-[10px] md:text-[12px] lg:text-[17px]">
                            Sumber
                        </span>
                        <span class="text-[8px] sm:text-[10px] md:text-[12px] lg:text-[17px]">
                            {{ $card['sumber'] }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-2">
            <x-dynamic-table :columns="$columns" :rows="$rows" />
        </div>
    </main>
</div>