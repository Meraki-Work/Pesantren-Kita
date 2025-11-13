@extends('index')

@section('title', 'Detail Notulen - PesantrenKita')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Detail Notulen Rapat</h2>
                        <p class="text-sm text-gray-600 mt-1">Informasi lengkap hasil rapat</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('notulen.index') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            ← Kembali
                        </a>
                        @if($notulen->canEdit())
                        <a href="{{ route('notulen.edit', $notulen->id_notulen) }}"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium">
                            Edit
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 space-y-6">
                <!-- Header Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $notulen->agenda }}</h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span><strong>Pimpinan:</strong> {{ $notulen->pimpinan }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <span><strong>Tempat:</strong> {{ $notulen->tempat }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-1 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span><strong>Tanggal:</strong> {{ $notulen->tanggal->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span><strong>Waktu:</strong> {{ $notulen->waktu_formatted }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span><strong>Dibuat oleh:</strong> {{ $notulen->user->username ?? 'System' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Gallery Dokumentasi -->
                @if($notulen->gambar && $notulen->gambar->count() > 0)
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Gallery Dokumentasi
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <!-- Thumbnail Gallery -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            @foreach($notulen->gambar as $index => $gambar)
                            <div class="relative group cursor-pointer" onclick="showImage('{{ $index }}')">
                                <img
                                    src="{{ asset('storage/' . $gambar->path_gambar) }}"
                                    alt="Dokumentasi rapat {{ $index + 1 }}"
                                    class="w-full h-24 object-cover rounded-lg border-2 border-transparent group-hover:border-blue-500 transition-all duration-200 bg-gray-200"
                                    onerror="this.onerror=null; this.src='https://via.placeholder.com/150?text=No+Image';">

                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3-3H7" />
                                    </svg>
                                </div>

                                <!-- Nomor urut -->
                                <div class="absolute bottom-1 right-1 bg-black/70 text-white text-xs px-2 py-1 rounded">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            @endforeach

                        </div>



                        <!-- Image Viewer -->
                        <div id="imageViewer" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4">
                            <div class="max-w-4xl max-h-full w-full">
                                <!-- Navigation -->
                                <div class="flex justify-between items-center mb-4">
                                    <button onclick="prevImage()" class="text-white hover:text-blue-300 transition-colors p-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>

                                    <div class="text-white text-center">
                                        <h4 id="imageTitle" class="text-lg font-semibold">{{ $notulen->agenda }}</h4>
                                        <p id="imageCounter" class="text-sm text-gray-300"></p>
                                    </div>

                                    <button onclick="nextImage()" class="text-white hover:text-blue-300 transition-colors p-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Main Image -->
                                <div class="flex items-center justify-center bg-black rounded-lg overflow-hidden">
                                    <img id="viewerImage" src="" alt="" class="max-w-full max-h-[70vh] object-contain"
                                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgdmlld0JveD0iMCAwIDQwMCAzMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI0MDAiIGhlaWdodD0iMzAwIiBmaWxsPSIjMTEyNTFBIi8+CjxwYXRoIGQ9Ik0xMDAgNzVIMzAwQzMyNS42ODkgNzUgMzQ1IDk0LjMxMDcgMzQ1IDEyMFYxODBDMzQ1IDIwNS42ODkgMzI1LjY4OSAyMjUgMzAwIDIyNUgxMDBDNzQuMzEwNyAyMjUgNTUgMjA1LjY4OSA1NSAxODBWMTIwQzU1IDk0LjMxMDcgNzQuMzEwNyA3NSAxMDAgNzVaIiBmaWxsPSIjMUQyQjNBIi8+CjxwYXRoIGQ9Ik0yMDAgMTE1QzIyMi4wOTEgMTE1IDI0MCAxMzIuOTA5IDI0MCAxNTVDMjQwIDE3Ny4wOTEgMjIyLjA5MSAxOTUgMjAwIDE5NUMxNzcuOTA5IDE5NSAxNjAgMTc3LjA5MSAxNjAgMTU1QzE2MCAxMzIuOTA5IDE3Ny45MDkgMTE1IDIwMCAxMTVaIiBmaWxsPSIjM0E0MjRBIi8+CjxwYXRoIGQ9Ik0yNzUgMTY1TDIyMy4zNzUgMjE2LjYyNUMyMjEuNDUzIDIxOC41NDcgMjE4LjU0NyAyMTguNTQ3IDIxNi42MjUgMjE2LjYyNUwxODUgMTg1TDE1My4zNzUgMjE2LjYyNUMxNTEuNDUzIDIxOC41NDcgMTQ4LjU0NyAyMTguNTQ3IDE0Ni42MjUgMjE2LjYyNUwxMzUgMjA1VjIyNUgyNzVWMTY1WiIgZmlsbD0iIzNBNEI0QSIvPgo8L3N2Zz4K'">
                                </div>

                                <!-- Thumbnail Strip -->
                                <div class="flex justify-center mt-4 space-x-2 overflow-x-auto py-2">
                                    @foreach($notulen->gambar as $index => $gambar)
                                    <img src="{{ asset('storage/' . $gambar->path_gambar) }}"
                                        alt="Thumbnail {{ $index + 1 }}"
                                        class="w-16 h-16 object-cover rounded cursor-pointer border-2 border-transparent hover:border-blue-400 transition-all duration-200 thumbnail"
                                        data-index="{{ $index }}"
                                        onclick="showImage('{{ $index }}')"
                                        onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjY0IiBoZWlnaHQ9IjY0IiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik00MiA0Mkg0MkM0NS4zMTM3IDQyIDQ4IDM5LjMxMzcgNDggMzZDMzYgMzAuNjg2MyAzMy4zMTM3IDI4IDMwIDI4QzI2LjY4NjMgMjggMjQgMzAuNjg2MyAyNCAzNEMyNCAzNy4zMTM3IDI2LjY4NjMgNDAgMzAgNDBINDJaIiBmaWxsPSIjOUNBQUFGIi8+CjxwYXRoIGQ9Ik0yNCA0MEwzMCA0Nkw0MiAzNEw0OCA0MFY1NEgyNFY0MFoiIGZpbGw9IiNFNkU3RTgiLz4KPC9zdmc+'">
                                    @endforeach
                                </div>

                                <!-- Close Button -->
                                <button onclick="closeViewer()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <!-- Image Info -->
                                <div class="mt-4 text-center text-white">
                                    <p id="imageDescription" class="text-sm text-gray-300"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Peserta -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Daftar Peserta
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-line">{{ $notulen->peserta }}</p>
                    </div>
                </div>

                <!-- Alur Rapat -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Alur / Proses Rapat
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-line">{{ $notulen->alur_rapat }}</p>
                    </div>
                </div>

                <!-- Hasil Rapat -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Hasil & Keputusan Rapat
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-line">{{ $notulen->hasil }}</p>
                    </div>
                </div>

                <!-- Keterangan -->
                @if($notulen->keterangan)
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Keterangan Tambahan
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-line">{{ $notulen->keterangan }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>Dibuat pada: {{ $notulen->created_at->format('d M Y H:i') }}</span>
                    <div class="flex space-x-3">
                        <button onclick="window.print()"
                            class="text-blue-600 hover:text-blue-800 font-medium">
                            Cetak
                        </button>
                        <a href="{{ route('notulen.export', $notulen->id_notulen) }}"
                            class="text-green-600 hover:text-green-800 font-medium">
                            Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Image viewer functionality
    let currentImageIndex = 0;

    // Gunakan template literal agar Blade dan JS tidak konflik
    const images = JSON.parse(`{!! json_encode(
        $notulen->gambar->map(function ($gambar) {
            return [
                'src' => asset('storage/' . $gambar->path_gambar),
                'keterangan' => $gambar->keterangan ?? 'Dokumentasi rapat',
                'created_at' => $gambar->created_at
                    ? \Carbon\Carbon::parse($gambar->created_at)->format('d M Y')
                    : '',
            ];
        })
        ->values(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    ) !!}`);

    function showImage(index) {
        currentImageIndex = index;
        const image = images[index];

        // Set image source
        document.getElementById('viewerImage').src = image.src;

        // Update counter
        document.getElementById('imageCounter').textContent =
            'Gambar ' + (index + 1) + ' dari ' + images.length;

        // Update description
        document.getElementById('imageDescription').textContent = image.keterangan;

        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
            if (i === index) {
                thumb.classList.add('border-blue-500');
                thumb.classList.remove('border-transparent');
            } else {
                thumb.classList.remove('border-blue-500');
                thumb.classList.add('border-transparent');
            }
        });

        // Show viewer
        document.getElementById('imageViewer').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeViewer() {
        document.getElementById('imageViewer').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function nextImage() {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        showImage(currentImageIndex);
    }

    function prevImage() {
        currentImageIndex =
            (currentImageIndex - 1 + images.length) % images.length;
        showImage(currentImageIndex);
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        const viewer = document.getElementById('imageViewer');
        if (!viewer.classList.contains('hidden')) {
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'Escape') closeViewer();
        }
    });

    // Close on background click
    document.getElementById('imageViewer').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) closeViewer();
    });

    // Preload images for better performance
    window.addEventListener('load', () => {
        images.forEach((image) => {
            const img = new Image();
            img.src = image.src;
        });
    });
</script>

<style>
    @media print {

        .bg-gradient-to-r,
        .flex.justify-between,
        button,
        a {
            display: none !important;
        }

        .bg-white {
            box-shadow: none !important;
            border: 1px solid #000 !important;
        }

        /* Hide image viewer in print */
        #imageViewer {
            display: none !important;
        }
    }

    /* Custom scrollbar for thumbnails */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Smooth transitions */
    .thumbnail {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection