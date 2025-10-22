@props(['columns' => [], 'rows' => [], 'tableData' => null])



<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <!-- Table Header dengan Info -->
    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Data Keuangan</h3>
                <p class="text-sm text-gray-600 mt-1">
                    @if($tableData && $tableData->total() > 0)
                    Total {{ $tableData->total() }} transaksi keuangan
                    @else
                    Total {{ count($rows) }} transaksi keuangan
                    @endif
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="bg-white rounded-lg px-3 py-1 shadow-sm">
                    <span class="text-sm font-medium text-gray-700">
                        @if($tableData && $tableData->total() > 0)
                        Halaman {{ $tableData->currentPage() }} dari {{ $tableData->lastPage() }}
                        @else
                        {{ count($rows) }} transaksi
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Search Input dengan Debounce -->
        <div class="mt-3 flex items-center justify-between">
            <div class="relative max-w-md">
                <input type="text"
                    id="searchInput"
                    placeholder="Cari transaksi"
                    class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <!-- Loading Indicator -->
                <div id="searchLoading" class="absolute right-3 top-2.5 hidden">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                </div>
            </div>
            <div class="gap-2 flex">
                <div class="h-10">
                    <a href="/kategori" class="w-full h-full px-5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition inline-flex items-center justify-center">
                        <span>Tambah Kategori</span>
                    </a>
                </div>
                <div>
                    <a href="{{ route('keuangan.create') }}" class="w-full h-full px-5 bg-[#2ECC71] text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition inline-flex items-center justify-center">
                        <span>Tambah Keuangan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <!-- Table Header -->
            <thead class="bg-gray-50/80 backdrop-blur-sm">
                <tr>
                    @foreach ($columns as $index => $col)
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200 
                               {{ $index === 0 ? 'rounded-tl-lg' : '' }} 
                               {{ $index === count($columns) - 1 ? 'rounded-tr-lg' : '' }}">
                        <div class="flex items-center space-x-1">
                            <span>{{ $col }}</span>
                        </div>
                    </th>
                    @endforeach
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200 rounded-tr-lg">
                        Aksi
                    </th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="divide-y divide-gray-100" id="tbodyData">
                @foreach($rows as $row)
                <tr class="group transition-all duration-200 hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-indigo-50/50 
                          {{ $loop->even ? 'bg-white' : 'bg-gray-50/30' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-[#2ECC71] rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                {{ substr($row['user'], 0, 1) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $row['user'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                              {{ $row['status'] === 'Masuk' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $row['jumlah'] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $row['kategori'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="font-medium text-gray-900">{{ $row['sumber_dana'] }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="font-medium text-gray-900">{{ $row['keterangan'] }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="text-gray-700 font-mono text-sm">{{ $row['tanggal'] }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $row['status'] === 'Masuk' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                            @if($row['status'] === 'Masuk')
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            @else
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                            @endif
                            {{ $row['status'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('keuangan.edit', $row['id']) }}"
                                class="text-blue-600 hover:text-blue-900 transition duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('keuangan.destroy', $row['id']) }}"
                                method="POST"
                                class="inline"
                                data-id="{{ $row['id'] }}">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    data-id="{{ $row['id'] }}"
                                    class="btn-delete text-red-600 hover:text-red-900 transition duration-200 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Empty State -->
    @if(count($rows) === 0)
    <div class="px-6 py-12 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h4 class="text-lg font-medium text-gray-600 mb-2">Tidak ada data transaksi</h4>
            <p class="text-gray-500 text-sm">Data transaksi keuangan akan muncul di sini ketika tersedia</p>
        </div>
    </div>
    @endif

    <!-- Debug Info (Sementara) -->
    @if($tableData)
    <div class="px-6 py-2 bg-yellow-50 text-xs text-yellow-700">
        Debug: total={{ $tableData->total() }}, currentPage={{ $tableData->currentPage() }}, lastPage={{ $tableData->lastPage() }}, hasPages={{ $tableData->hasPages() ? 'true' : 'false' }}
    </div>
    @endif

    <!-- Pagination Section - FIXED -->
    @if($tableData && $tableData->lastPage() > 1)
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200" style="display: block !important;">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
            <!-- Info Jumlah Data -->
            <div class="text-sm text-gray-600">
                Menampilkan
                <span class="font-medium">{{ $tableData->firstItem() }}</span>
                -
                <span class="font-medium">{{ $tableData->lastItem() }}</span>
                dari
                <span class="font-medium">{{ $tableData->total() }}</span>
                transaksi
            </div>

            <!-- Simple Pagination -->
            <div class="flex items-center space-x-2">
                <!-- Previous Button -->
                @if($tableData->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Sebelumnya
                </span>
                @else
                <a href="{{ $tableData->previousPageUrl() }}"
                    class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Sebelumnya
                </a>
                @endif

                <!-- Page Info -->
                <span class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg">
                    Halaman {{ $tableData->currentPage() }} dari {{ $tableData->lastPage() }}
                </span>

                <!-- Next Button -->
                @if($tableData->hasMorePages())
                <a href="{{ $tableData->nextPageUrl() }}"
                    class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 inline-flex items-center">
                    Selanjutnya
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed inline-flex items-center">
                    Selanjutnya
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
                @endif
            </div>
        </div>
    </div>
    @endif
    <!-- Summary Footer -->
    <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
        <div class="flex justify-between items-center text-sm text-gray-600">
            <span>Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</span>
            <div class="flex space-x-4">
                <span class="flex items-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                    Pemasukan
                </span>
                <span class="flex items-center">
                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                    Pengeluaran
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Include SweetAlert2 for better alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete button handler
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                confirmDelete(id);
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.querySelector(`form[data-id="${id}"]`);
                    if (form) form.submit();
                }
            });
        }
    });
</script>




<style>
    @media print {

        .bg-gradient-to-r,
        button,
        .flex.justify-center.space-x-2,
        a,
        .pagination-section {
            display: none !important;
        }

        .bg-white {
            box-shadow: none !important;
            border: 1px solid #000 !important;
        }
    }

    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>