@extends('layouts.kepegawaian')

@php
    // DATA DUMMY FRONTEND ONLY
    $employees = [
        [
            'nama' => 'Ahmad Fauzi, S.Pd.I',
            'nik' => '198501152010011001',
            'jabatan' => 'Ustadz',
            'status' => 'Aktif',
            'email' => 'ahmad@gmail.com',
            'kontak' => '082567324374',
            'alamat' => 'Batam Center'
        ],
        [
            'nama' => 'Muhammad Yusuf',
            'nik' => '199508201510013001',
            'jabatan' => 'Ustadz Tahfidz',
            'status' => 'Aktif',
            'email' => 'myusuf@gmail.com',
            'kontak' => '082566554323',
            'alamat' => 'Batu Aji'
        ],
        [
            'nama' => 'Siti Aminah',
            'nik' => '199702112019032002',
            'jabatan' => 'Administrasi',
            'status' => 'Cuti',
            'email' => 'aminah@gmail.com',
            'kontak' => '081212334455',
            'alamat' => 'Sagulung'
        ],
    ];
@endphp

@section('content')

<div class="px-3 py-4 w-full max-w-full">

    {{-- Header User --}}
    <div class="flex items-center space-x-4 mb-8">
        <img 
            src="/images/profile.jpg" 
            alt="User Profile" 
            class="h-10 w-10 rounded-full object-cover"
        />
        <span class="font-semibold text-gray-900 text-sm">AL Amal Batam</span>
    </div>

    {{-- Cards Statistik --}}
    <div class="grid grid-cols-4 gap-6 mb-10">

        {{-- Card 1 --}}
        <div class="flex items-center border border-gray-300 rounded-lg px-5 py-4 bg-white shadow-sm">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-8 w-8 text-green-700" 
                     fill="none" stroke="currentColor" stroke-width="1.5" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M7 10a4 4 0 118 0 4 4 0 01-8 0z" />
                </svg>
            </div>

            <div class="flex items-center ml-4 space-x-3">
                <div class="w-1 h-10 bg-green-600 rounded"></div>
                <div>
                    <p class="text-xs text-gray-700 font-semibold">Jumlah Pegawai Aktif</p>
                    <p class="text-lg font-bold text-gray-900">4</p>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="flex items-center border border-gray-300 rounded-lg px-5 py-4 bg-white shadow-sm">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-8 w-8 text-green-700" 
                     fill="none" stroke="currentColor" stroke-width="1.5" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M7 10a4 4 0 118 0 4 4 0 01-8 0z" />
                </svg>
            </div>

            <div class="flex items-center ml-4 space-x-3">
                <div class="w-1 h-10 bg-green-600 rounded"></div>
                <div>
                    <p class="text-xs text-gray-700 font-semibold">Jumlah Pegawai Non-Aktif</p>
                    <p class="text-lg font-bold text-gray-900">1</p>
                </div>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="flex items-center border border-gray-300 rounded-lg px-5 py-4 bg-white shadow-sm">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-8 w-8 text-green-700" 
                     fill="none" stroke="currentColor" stroke-width="1.5" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M7 10a4 4 0 118 0 4 4 0 01-8 0z" />
                </svg>
            </div>

            <div class="flex items-center ml-4 space-x-3">
                <div class="w-1 h-10 bg-green-600 rounded"></div>
                <div>
                    <p class="text-xs text-gray-700 font-semibold">Jumlah Keseluruhan Pegawai</p>
                    <p class="text-lg font-bold text-gray-900">5</p>
                </div>
            </div>
        </div>

        {{-- Card Tambah Pegawai --}}
        <button 
            class="flex items-center justify-center border border-gray-300 rounded-lg px-5 py-4 bg-white shadow-sm hover:bg-gray-50 transition"
            x-data
            @click="$dispatch('open-modal')"
        >
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-green-500 text-white rounded-[15px] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        fill="none" viewBox="0 0 24 24" 
                        stroke-width="2" stroke="currentColor" 
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Tambahkan Pegawai</span>
            </div>
        </button>

    </div>

    {{-- Search Bar --}}
    <div class="mb-6">
        <div class="flex items-center bg-white border border-gray-300 rounded-lg px-3 py-2 w-full max-w-xl shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-5 w-5 text-gray-400 mr-3"
                 fill="none" viewBox="0 0 24 24" 
                 stroke="currentColor">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>

            <input type="search"
                placeholder="Cari Data Pegawai..."
                class="flex-grow outline-none text-sm text-gray-700 placeholder-gray-400"
            />
        </div>
    </div>

    {{-- Tabel Pegawai --}}
    <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">

            <thead class="bg-[#1B4332] text-white">
                <tr>
                    <th class="px-3 py-3 text-left">Nama Lengkap</th>
                    <th class="px-6 py-3 text-center">NIK/NIP</th>
                    <th class="px-6 py-3 text-left">Jabatan</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-center">Email</th>
                    <th class="px-6 py-3 text-left">Kontak</th>
                    <th class="px-6 py-3 text-left">Alamat</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-900">
                @foreach ($employees as $emp)
                <tr class="border-t">

                    <td class="px-6 py-4">{{ $emp['nama'] }}</td>
                    <td class="px-6 py-4">{{ $emp['nik'] }}</td>
                    <td class="px-6 py-4">{{ $emp['jabatan'] }}</td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        @if ($emp['status'] === 'Aktif')
                            <span class="bg-green-500 text-white px-3 py-1 text-xs rounded-full whitespace-nowrap">
                                Aktif
                            </span>
                        @else
                            <span class="bg-red-500 text-white px-3 py-1 text-xs rounded-full whitespace-nowrap">
                                Non-Aktif
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 font-medium">{{ $emp['email'] }}</td>
                    <td class="px-6 py-4">{{ $emp['kontak'] }}</td>
                    <td class="px-6 py-4">{{ $emp['alamat'] }}</td>

                    {{-- Button Aksi --}}
                    <td class="px-6 py-4 text-center" x-data="{ open: false }">

                        <button 
                            @click="open = !open"
                            class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 relative"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-5 w-5" 
                                 fill="currentColor" 
                                 viewBox="0 0 20 20">
                                <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                            </svg>
                        </button>

                        <div 
                            x-show="open"
                            @click.away="open = false"
                            class="absolute mt-2 right-10 bg-white shadow-md rounded-md w-28 py-2 z-50"
                        >
                            <button class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                Edit
                            </button>

                            <button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Hapus
                            </button>
                        </div>

                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>



{{-- MODAL TAMBAH PEGAWAI --}}
<div 
    x-data="{ open: false }"
    x-on:open-modal.window="open = true"
    x-show="open"
    class="fixed inset-0 flex items-center justify-center backdrop-blur-sm z-50"
    x-transition
>
    <div class="bg-white rounded-xl w-[750px] p-7 relative shadow-lg">

        {{-- Close Button --}}
        <button 
            @click="open = false"
            class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 text-2xl"
        >&times;</button>

        {{-- Header --}}
        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-6 w-6 text-green-700" 
                 fill="none" viewBox="0 0 24 24" 
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M12 4v16m8-8H4"/>
            </svg>
            Data Pegawai
        </h2>

        {{-- Form --}}
        <form action="" method="POST">
            @csrf

            {{-- Row 1 --}}
            <div class="grid grid-cols-2 gap-5 mb-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <input type="text" class="w-full bg-gray-200 p-2 rounded-lg mt-1" placeholder="Masukkan Nama Lengkap">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">NIP/NIK</label>
                    <input type="text" class="w-full bg-gray-200 p-2 rounded-lg mt-1" placeholder="Masukkan NIP/NIK">
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="grid grid-cols-2 gap-5 mb-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Jabatan</label>
                    <input type="text" class="w-full bg-gray-200 p-2 rounded-lg mt-1" placeholder="Masukkan Jabatan">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Status</label>
                    <select class="w-full bg-gray-200 p-2 rounded-lg mt-1">
                        <option>Pilih Status</option>
                        <option>Aktif</option>
                        <option>Non-Aktif</option>
                    </select>
                </div>
            </div>

            {{-- Row 3 --}}
            <div class="grid grid-cols-2 gap-5 mb-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Email</label>
                    <input type="email" class="w-full bg-gray-200 p-2 rounded-lg mt-1" placeholder="Masukkan Email">
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Kontak</label>
                    <input type="text" class="w-full bg-gray-200 p-2 rounded-lg mt-1" placeholder="Masukkan Nomor Kontak">
                </div>
            </div>

            {{-- Alamat --}}
            <div class="mb-6">
                <label class="text-sm font-semibold text-gray-700">Alamat</label>
                <textarea class="w-full bg-gray-200 p-2 rounded-lg mt-1 h-24" placeholder="Masukkan Alamat"></textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-4">
                <button 
                    type="button"
                    @click="open = false"
                    class="bg-red-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-600"
                >Batal</button>

                <button 
                    type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700"
                >Simpan</button>
            </div>

        </form>

    </div>
</div>

@endsection
