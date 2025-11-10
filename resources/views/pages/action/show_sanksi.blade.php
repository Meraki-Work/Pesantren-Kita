@extends('index')

@section('title', 'Detail Sanksi')

@section('content')
<div class="flex bg-gray-100 min-h-screen">

    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-4 h-full">
        <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Detail Data Sanksi</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('sangksi.edit', $sanksi->id_sanksi) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('sangksi') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Detail Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi User</h3>
                    <div class="space-y-2">

                        @if($sanksi->user && $sanksi->user->username)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Username</label>
                            <p class="text-gray-800">{{ $sanksi->user->username }}</p>
                        </div>
                        @endif
                        @if($sanksi->user && $sanksi->user->email)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-800">{{ $sanksi->user->email }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Sanksi Info -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Sanksi</h3>
                    <div class="space-y-2">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Tanggal</label>
                            <p class="text-gray-800">{{ $sanksi->tanggal->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Status</label>
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-medium {{ $sanksi->status == 'Aktif' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $sanksi->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Jenis Sanksi -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Jenis Sanksi</h3>
                    <p class="text-gray-800">{{ $sanksi->jenis }}</p>
                </div>

                <!-- Hukuman -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Hukuman</h3>
                    <p class="text-gray-800">{{ $sanksi->hukuman }}</p>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Deskripsi Pelanggaran</h3>
                    <p class="text-gray-800 whitespace-pre-line">{{ $sanksi->deskripsi }}</p>
                </div>

                <!-- Created Info -->
                @if($sanksi->ponpe)
                <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Pondok Pesantren</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Nama Ponpes</label>
                            <p class="text-gray-800">{{ $sanksi->ponpe->nama_ponpes ?? 'Tidak diketahui' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">ID Ponpes</label>
                            <p class="text-gray-800">{{ $sanksi->ponpes_id }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <form action="{{ route('sangksi.destroy', $sanksi->id_sanksi) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center" onclick="return confirm('Apakah Anda yakin ingin menghapus data sanksi ini?')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection