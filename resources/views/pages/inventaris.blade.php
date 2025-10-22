@extends('index')

@section('title', 'Inventaris')

@section('content')
<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6 space-y-3">

        {{-- Bagian utama kartu kategori --}}
        @php
        $sortedData = $data->sortByDesc('total_jumlah')->take(4);
        $count = $sortedData->count();
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full max-w-7xl mx-auto">
            {{-- Jika data ada --}}
            @foreach ($sortedData as $card)
            <div class="flex flex-col rounded-xl box-bg p-4 hover:shadow-md transition w-full">
                <div class="grid">
                    <span class="text-mint text-xs sm:text-sm lg:text-base">Kategori Aset</span>
                </div>
                <div class="flex justify-between items-center">
                    <label class="{{ strlen($card->kategori ?? '') > 14 ? 'text-base sm:text-lg' : 'sm:text-lg md:text-xl lg:text-2xl' }}">
                        {{ $card->kategori }}
                    </label>
                </div>
                <div class="mt-2">
                    <span class="font-medium text-lg sm:text-xl lg:text-2xl">
                        {{ number_format($card->total_jumlah, 0, ',', '.') }} Unit
                    </span>
                </div>
            </div>
            @endforeach

            {{-- Tambah placeholder jika kurang dari 4 --}}
            @for ($i = $count; $i < 4; $i++)
                <div class="flex flex-col rounded-xl box-bg p-4 hover:shadow-md transition w-full">
                <div class="grid">
                    <span class="text-mint text-xs sm:text-sm lg:text-base">Kategori Aset</span>
                </div>
                <div class="flex justify-between items-center">
                    <label class="text-lg sm:text-xl lg:text-2xl">-</label>
                </div>
                <div class="mt-2">
                    <span class="font-medium text-lg sm:text-xl lg:text-2xl">-</span>
                </div>
        </div>
        @endfor
</div>

<div x-data="{ openForm: '' }" class="box-bg p-4 rounded-xl max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-center">
        <span class="text-lg font-semibold">Tambahkan Items</span>
        <div class="flex gap-2 mt-2 sm:mt-0">
            <!-- Tombol Tambah Kategori -->
            <button 
                @click="openForm = openForm === 'kategori' ? '' : 'kategori'"
                :class="openForm === 'kategori' ? 'bg-green-700' : 'bg-green-500 hover:bg-green-700'"
                class="px-4 py-2 text-white rounded shadow-sm transition-colors">
                Tambah Kategori
            </button>

            <!-- Tombol Tambah Inventaris -->
            <button 
                @click="openForm = openForm === 'inventaris' ? '' : 'inventaris'"
                :class="openForm === 'inventaris' ? 'bg-green-700' : 'bg-green-500 hover:bg-green-700'"
                class="px-4 py-2 text-white rounded shadow-sm transition-colors">
                Tambah Inventaris
            </button>
        </div>
    </div>

    <!-- Form Kategori -->
    <div x-show="openForm === 'kategori'" x-transition class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 w-full max-w-6xl mx-auto mb-4">
        <input type="text" placeholder="Nama Kategori" class="input input-bordered w-full rounded-md p-2 sm:col-span-2" />
        <input type="text" placeholder="Deskripsi Kategori" class="input input-bordered w-full rounded-md p-2 sm:col-span-2 md:col-span-3" />
        <button @click="openForm = ''" class="px-4 py-2 bg-red-500 text-white rounded shadow-sm hover:bg-red-700 sm:col-span-2 md:col-span-1">
            Batal
        </button>
    </div>

    <!-- Form Inventaris -->
    <div x-show="openForm === 'inventaris'" x-transition class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 w-full max-w-6xl mx-auto">
        <input type="text" placeholder="Nama Barang" class="input input-bordered w-full rounded-md p-2 sm:col-span-2 md:col-span-2 lg:col-span-2" />
        <input type="text" placeholder="Kategori" class="input input-bordered w-full rounded-md p-2" />
        <input type="number" placeholder="Jumlah" class="input input-bordered w-full rounded-md p-2" />
        <input type="text" placeholder="Kondisi" class="input input-bordered w-full rounded-md p-2" />
        <input type="text" placeholder="Lokasi" class="input input-bordered w-full rounded-md p-2 sm:col-span-2 md:col-span-2 lg:col-span-2" />
        <input type="date" class="input input-bordered w-full rounded-md p-2" />
        <input type="text" placeholder="Keterangan" class="input input-bordered w-full rounded-md p-2 sm:col-span-2 md:col-span-3 lg:col-span-4" />
        <button @click="openForm = ''" class="px-4 py-2 bg-red-500 text-white rounded shadow-sm hover:bg-red-700 sm:col-span-2 md:col-span-1 lg:col-span-1">
            Batal
        </button>
    </div>
</div>

</main>
</div>
@endsection