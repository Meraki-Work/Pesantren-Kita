@extends('index')

@section('title', 'Inventaris - PesantrenKita')

@section('content')
<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" class="h-full" />

    <main class="flex-1 p-6 overflow-y-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Inventaris</h1>
            <a href="{{ route('inventaris.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                + Tambah Inventaris
            </a>
        </div>

        @php
        $total = $inventaris->total() ?? 0;
        $kategoriCount = $kategories->count() ?? 0;
        @endphp

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="box-bg p-4">
                <p class="text-base sm:text-lg' : 'text-md sm:text-sm lg:text-xl">Total Barang</p>
                <h3 class="pt-2 font-medium text-lg sm:text-xl lg:text-3xl">{{ $total }} Unit</h3>
            </div>
            <div class="box-bg p-4">
                <p class="text-base sm:text-lg' : 'text-md sm:text-sm lg:text-xl">Kategori</p>
                <h3 class="pt-2 font-medium text-lg sm:text-xl lg:text-3xl">{{ $kategoriCount }}</h3>
            </div>
            <div class="box-bg p-4">
                <p class="text-base sm:text-lg' : 'text-md sm:text-sm lg:text-xl">Kondisi Baik</h3>
                <h3 class="pt-2 font-medium text-lg sm:text-xl lg:text-3xl">{{ $inventaris->where('kondisi', 'Baik')->sum('jumlah') }} Unit</h3>
            </div>
            <div class="box-bg p-4">
                <h3 class="text-base sm:text-lg' : 'text-md sm:text-sm lg:text-xl">Perlu Perbaikan</h3>
                <p class="pt-2 font-medium text-lg sm:text-xl lg:text-3xl">
                    {{ $inventaris->where('kondisi', 'Rusak')->sum('jumlah') }} Unit
                </p>
            </div>
        </div>

        <!-- Filters -->
        <div class="box-bg p-4 rounded-lg mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" placeholder="Cari barang..."
                    value="{{ $search ?? '' }}" class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">

                <select name="kategori" class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    <option value="">Semua Kategori</option>
                    @foreach($kategories as $kat)
                    <option value="{{ $kat }}" {{ ($kategori ?? '') == $kat ? 'selected' : '' }}>
                        {{ $kat }}
                    </option>
                    @endforeach
                </select>

                <select name="kondisi" class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                    <option value="">Semua Kondisi</option>
                    @foreach($kondisis as $kon)
                    <option value="{{ $kon }}" {{ ($kondisi ?? '') == $kon ? 'selected' : '' }}>
                        {{ $kon }}
                    </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700 transition">
                    Filter
                </button>
            </form>
            <div class="bg-white rounded-lg shadow overflow-hidden mt-4">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Nama Barang</th>
                            <th class="px-6 py-3 text-left font-semibold">Kategori</th>
                            <th class="px-6 py-3 text-left font-semibold">Kondisi</th>
                            <th class="px-6 py-3 text-left font-semibold">Jumlah</th>
                            <th class="px-6 py-3 text-left font-semibold">Lokasi</th>
                            <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($inventaris as $item)
                        <tr>
                            <td class="px-6 py-4 truncate">{{ $item->nama_barang }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                                    {{ $item->kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    @if($item->kondisi == 'Baik') bg-green-100 text-green-800
                                    @elseif($item->kondisi == 'Rusak')
                                    @elseif($item->kondisi == 'Hilang')
                                    @endif">
                                    {{ $item->kondisi }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $item->jumlah }}</td>
                            <td class="px-6 py-4">{{ $item->lokasi }}</td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-3">
                                    <a href="{{ route('inventaris.edit', $item->id_inventaris) }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('inventaris.destroy', $item->id_inventaris) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 font-medium"
                                            onclick="return confirm('Hapus data ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">
                                Tidak ada data inventaris ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($inventaris->hasPages())
                <div class="px-6 py-4 bg-gray-50">
                    {{ $inventaris->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection