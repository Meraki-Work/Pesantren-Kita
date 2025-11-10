@extends('index')

@section('title', 'Notulen Rapat - PesantrenKita')

@section('content')
<div class="flex bg-gray-100 min-h-screen">

    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6 h-full">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Notulen Rapat</h1>
                <p class="text-sm text-gray-600 mt-1">Catatan dan dokumentasi rapat pesantren</p>
            </div>
            <a href="{{ route('notulen.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-medium">
                + Buat Notulen
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="mt-3 grid sm:grid-cols-3 lg:grid-cols-3 gap-4 mb-6">
            <div class="box-bg bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6">
                <div class="flex items-center">
                    <!-- <span class="w-2 h-full bg-green-500 rounded-xl flex"></span>     -->
                    <div>
                        <p class="text-base sm:text-lg' : 'text-lg sm:text-xl lg:text-2xl">Total Notulen</p>
                        <p class="font-medium text-lg sm:text-xl lg:text-2xl">{{ $notulen->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="box-bg bg-gradient-to-r from-[#2ECC71] to-[#17b459] rounded-xl p-6">
                <div class="flex items-center">
                    <div>
                        <p class="text-base sm:text-lg' : 'text-lg sm:text-xl lg:text-2xl">Bulan Ini</p>
                        <p class="font-medium text-lg sm:text-xl lg:text-2xl">
                            {{ $notulen->where('tanggal', '>=', now()->startOfMonth())->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="box-bg bg-[#344E41] rounded-xl p-6">
                <div class="flex items-center">
                    <div>
                        <p class="text-base sm:text-lg' : 'text-lg sm:text-xl lg:text-2xl">Minggu Ini</p>
                        <p class="font-medium text-lg sm:text-xl lg:text-2xl">
                            {{ $notulen->where('tanggal', '>=', now()->startOfWeek())->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            @foreach($recentlyGambar as $index => $gambar)
            <div class="mb-3 box-bg overflow-hidden hover: transition-all duration-300 relative">

                <!-- Badge TERBARU untuk item pertama -->
                @if($index === 0)
                <div class="absolute top-3 right-3 z-10">
                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-medium animate-pulse">
                        🔥 TERBARU
                    </span>
                </div>
                @endif

                <!-- Gambar -->
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ asset('storage/' . $gambar->path_gambar) }}"
                        alt="{{ $gambar->notulen->agenda ?? 'Dokumentasi rapat' }}"
                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">

                    <!-- Overlay tanggal -->
                    <div class="absolute bottom-3 right-3">
                        <span class="bg-black/70 text-white px-2 py-1 rounded text-xs">
                            {{ $gambar->created_at ? \Carbon\Carbon::parse($gambar->created_at)->format('d M') : '' }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-5">
                    <!-- Agenda -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">
                        {{ $gambar->notulen->agenda ?? 'Agenda tidak tersedia' }}
                    </h3>

                    <!-- Pimpinan -->
                    <div class="flex items-center text-sm text-gray-600 mb-3">
                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-medium">{{ $gambar->notulen->pimpinan ?? 'Tidak ada pimpinan' }}</span>
                    </div>

                    <!-- Keterangan -->
                    <p class="text-sm text-gray-600 line-clamp-3 mb-4">
                        {{ $gambar->keterangan ?? 'Dokumentasi rapat pesantren' }}
                    </p>

                    <!-- Footer -->
                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $gambar->created_at ? \Carbon\Carbon::parse($gambar->created_at)->diffForHumans() : '' }}</span>
                        </div>
                        @forelse($notulen as $item)
                        <button class="text-blue-600 hover:text-blue-800 font-medium">
                            <a href="{{ route('notulen.show', $item->id_notulen) }}">Lihat Detail →</a>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Recent Rapat & Gallery -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Filters -->
                <div class="box-bg border border-gray-100 p-4">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="text" name="search" placeholder="Cari agenda, peserta..."
                            value="{{ $search }}" class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                        <input type="date" name="tanggal"
                            value="{{ $tanggal }}" class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                        <select name="pimpinan" class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Semua Pimpinan</option>
                            @foreach($pimpinans as $pimp)
                            <option value="{{ $pimp }}" {{ $pimpinan == $pimp ? 'selected' : '' }}>
                                {{ $pimp }}
                            </option>
                            @endforeach
                        </select>

                        <button type="submit" class="bg-blue-600 text-white rounded-lg px-4 py-3 hover:bg-blue-700 transition font-medium">
                            Filter
                        </button>
                    </form>
                </div>

                <!-- Recent Rapat Table -->
                <div class="box-bg border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Rapat</h3>
                        <p class="text-sm text-gray-600">Daftar notulen rapat terbaru</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Agenda</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pimpinan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal & Waktu</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tempat</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($notulen as $item)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ Str::limit($item->agenda, 60) }}</div>
                                                <div class="text-sm text-gray-500 mt-1">
                                                    Oleh: {{ $item->user->username ?? 'System' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $item->pimpinan }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $item->waktu }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-700">{{ $item->tempat }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('notulen.show', $item->id_notulen) }}"
                                                class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                Lihat
                                            </a>
                                            @if($item->user_id === auth()->id())
                                            <a href="{{ route('notulen.edit', $item->id_notulen) }}"
                                                class="text-blue-600 hover:text-blue-900 transition duration-200 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('notulen.destroy', $item->id_notulen) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn-delete text-red-600 hover:text-red-900 transition duration-200 inline-flex items-center"
                                                    onclick="return confirm('Hapus notulen ini?')">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-lg font-medium">Belum ada notulen rapat</p>
                                            <p class="mt-1">Mulai dengan membuat notulen rapat pertama Anda</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($notulen->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $notulen->links() }}
                    </div>
                    @endif
                </div>
            </div>



            <!-- Right Column - Gallery & Quick Stats -->
            <div class="space-y-6">
                <!-- Gallery Section -->
                <div class="box-bg border border-gray-100">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Gallery Dokumentasi</h3>
                        <p class="text-sm text-gray-600">Foto-foto rapat terbaru</p>
                    </div>
                    <div class="p-4">
                        @if($recentGambar->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentGambar as $gambar)
                            <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                                <div class="flex space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($gambar->path_gambar)
                                        <img src="{{ asset('storage/' . $gambar->path_gambar) }}"
                                            alt="Dokumentasi rapat"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-300 cursor-pointer"
                                            data-image="{{ asset('storage/' . $gambar->path_gambar) }}"
                                            data-title="{{ $gambar->notulen->agenda ?? 'Tidak ada judul' }}"
                                            onclick="showImageModal(this)">
                                        @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg border border-gray-300 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $gambar->notulen->agenda ?? 'Agenda tidak tersedia' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $gambar->keterangan ?? 'Dokumentasi rapat' }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $gambar->created_at ? \Carbon\Carbon::parse($gambar->created_at)->format('d M Y') : 'Tanggal tidak tersedia' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-center">
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat Semua Gallery →
                            </a>
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-500 text-sm">Belum ada dokumentasi</p>
                            <p class="text-gray-400 text-xs mt-1">Upload gambar saat membuat notulen</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="box-bg border border-gray-100">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Statistik Cepat</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Dokumentasi</span>
                            <span class="font-semibold text-blue-600">{{ $totalGambar }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Rapat Bulan Ini</span>
                            <span class="font-semibold text-green-600">{{ $rapatBulanIni }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Pimpinan Teraktif</span>
                            <span class="font-semibold text-purple-600 text-xs text-right max-w-[100px] truncate">
                                {{ $topPimpinan->pimpinan ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="box-bg border border-gray-100">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ Str::limit($activity->agenda, 40) }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $activity->created_at ? \Carbon\Carbon::parse($activity->created_at)->diffForHumans() : 'Tanggal tidak tersedia' }} • {{ $activity->user->username ?? 'System' }}
                                </p>
                            </div>
                        </div>
                        @endforeach

                        @if($recentActivities->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm">Belum ada aktivitas</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div id="imageModal" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-hidden">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h4 id="modalTitle" class="text-lg font-semibold text-gray-800"></h4>
                    <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 flex items-center justify-center min-h-[200px]">
                    <img id="modalImage" src="" alt="" class="max-w-full max-h-[70vh] object-contain">
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function showImageModal(imageSrc, title) {
        console.log('Showing modal with image:', imageSrc);
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });

    // Close modal on background click
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    function showImageModal(element) {
        const imageUrl = element.getAttribute('data-image');
        const imageTitle = element.getAttribute('data-title');

        // Lanjutkan dengan kode modal Anda
        console.log('Image URL:', imageUrl);
        console.log('Image Title:', imageTitle);

        // Contoh membuka modal
        // document.getElementById('modalImage').src = imageUrl;
        // document.getElementById('modalTitle').textContent = imageTitle;
        // document.getElementById('imageModal').classList.remove('hidden');
    }

    // Debug function to check images
    function checkImages() {
        const images = document.querySelectorAll('img');
        images.forEach((img, index) => {
            console.log(`Image ${index}:`, img.src, img.complete ? 'loaded' : 'loading');
        });
    }

    // Check images on load
    document.addEventListener('DOMContentLoaded', function() {
        checkImages();
    });
</script>

<style>
    .box-bg {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    #imageModal {
        backdrop-filter: blur(4px);
    }
</style>
@endsection