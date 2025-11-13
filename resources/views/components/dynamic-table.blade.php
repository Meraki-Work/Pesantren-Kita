@props(['columns' => [], 'rows' => []])

<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <!-- Table Header dengan Info -->
    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Data Santri</h3>
                <p class="text-sm text-gray-600 mt-1">Total {{ count($rows) }} data santri</p>
            </div>


        </div>

        <!-- Search Input dengan Debounce -->
        <div class="mt-3">
            <input type="text"
                id="searchInput"
                placeholder="Cari santri ..."
                class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <!-- Loading Indicator -->
            <div id="searchLoading" class="absolute right-3 top-2.5 hidden">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
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
                            @if($index === 0)
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                            @endif
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
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="hidden px-6 py-12 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
            <h4 class="text-lg font-medium text-gray-600 mb-2">Tidak ada data</h4>
            <p class="text-gray-500 text-sm">Data santri akan muncul di sini ketika tersedia</p>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden px-6 py-8">
        <div class="flex items-center justify-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-600">Memuat data...</span>
        </div>
    </div>

    <!-- Search Results Info -->
    <div id="searchInfo" class="hidden px-6 py-3 bg-yellow-50 border-t border-yellow-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm text-yellow-700" id="searchResultText"></span>
            </div>
            <button onclick="clearSearch()" class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">
                Tampilkan Semua
            </button>
        </div>
    </div>
</div>

<div id="table-data" data-rows='@json($rows)' class="hidden"></div>

<script type="text/javascript">
    const allData = JSON.parse(document.getElementById('table-data').dataset.rows);
    const tbody = document.getElementById('tbodyData');
    const emptyState = document.getElementById('emptyState');
    const loadingState = document.getElementById('loadingState');
    const searchInput = document.getElementById('searchInput');
    const searchLoading = document.getElementById('searchLoading');
    const searchInfo = document.getElementById('searchInfo');
    const searchResultText = document.getElementById('searchResultText');

    let searchTimeout = null;
    let currentSearchTerm = '';

    // Format functions
    const formatCell = (value) => {
        if (value === null || value === undefined || value === '') return '-';
        return value;
    };

    const createBadge = (text, type = 'default') => {
        const colors = {
            default: 'bg-gray-100 text-gray-800',
            success: 'bg-green-100 text-green-800',
            warning: 'bg-yellow-100 text-yellow-800',
            danger: 'bg-red-100 text-red-800',
            info: 'bg-blue-100 text-blue-800'
        };

        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${colors[type]}">${text}</span>`;
    };

    function renderTable(data) {
        // Show loading
        loadingState.classList.remove('hidden');
        tbody.innerHTML = '';
        emptyState.classList.add('hidden');
        searchInfo.classList.add('hidden');

        setTimeout(() => {
            loadingState.classList.add('hidden');

            if (!data.length) {
                tbody.classList.add('hidden');
                emptyState.classList.remove('hidden');

                // Show search info if searching
                if (currentSearchTerm) {
                    searchInfo.classList.remove('hidden');
                    searchResultText.textContent = `Tidak ditemukan hasil untuk "${currentSearchTerm}"`;
                }
                return;
            }

            tbody.classList.remove('hidden');

            // Show search info if searching
            if (currentSearchTerm) {
                searchInfo.classList.remove('hidden');
                searchResultText.textContent = `Menampilkan ${data.length} hasil untuk "${currentSearchTerm}"`;
            }

            data.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.className = `group transition-all duration-200 hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-indigo-50/50 
                               ${index % 2 === 0 ? 'bg-white' : 'bg-gray-50/30'}`;

                // Render data cells dengan styling yang lebih baik
                const dataCells = row.data.map((item, cellIndex) => {
                    let cellContent = formatCell(item);

                    // Styling khusus berdasarkan konten
                    if (cellIndex === 1) { // Kolom Nama
                        // Highlight search term in name
                        if (currentSearchTerm) {
                            const regex = new RegExp(`(${currentSearchTerm})`, 'gi');
                            cellContent = cellContent.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
                        }
                        cellContent = `<span class="font-semibold text-gray-900">${cellContent}</span>`;
                    } else if (cellIndex === 4) { // Kolom Tahun Masuk
                        cellContent = `<span class="font-medium text-blue-600">${cellContent}</span>`;
                    } else if (cellIndex === 5) { // Kolom Jenis Kelamin
                        const badgeType = item === 'Laki-laki' ? 'info' : 'warning';
                        cellContent = createBadge(item, badgeType);
                    } else if (cellIndex === 6) { // Kolom Tanggal Lahir
                        cellContent = `<span class="text-gray-700">${cellContent}</span>`;
                    }

                    return `<td class="px-6 py-4 whitespace-nowrap text-sm transition-colors duration-200 group-hover:text-gray-900">
                                <div class="flex items-center">${cellContent}</div>
                            </td>`;
                }).join('');

                // Action buttons dengan design yang lebih modern
                const actionButtons = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <button onclick="editSantri(${row.id})" 
                                    class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg 
                                           hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 
                                           shadow-sm hover:shadow-md text-xs font-medium">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                            <button onclick="deleteSantri(${row.id})" 
                                    class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg 
                                           hover:from-red-600 hover:to-red-700 transition-all duration-200 transform hover:scale-105 
                                           shadow-sm hover:shadow-md text-xs font-medium">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </div>
                        <div class="flex justify-center space-x-2 opacity-100 group-hover:opacity-0 transition-opacity duration-200">
                            <span class="text-xs text-gray-400">Hover untuk aksi</span>
                        </div>
                    </td>
                `;

                tr.innerHTML = dataCells + actionButtons;
                tbody.appendChild(tr);
            });
        }, 500); // Simulate loading delay
    }

    // Debounced search function dengan delay 5 detik
    function performSearch(searchTerm) {
        currentSearchTerm = searchTerm.trim();

        if (!currentSearchTerm) {
            renderTable(allData);
            return;
        }

        searchLoading.classList.remove('hidden');

        // Filter data berdasarkan search term
        const filteredData = allData.filter(row =>
            row.data.some(cell =>
                cell?.toString().toLowerCase().includes(currentSearchTerm.toLowerCase())
            )
        );

        // Simulate network delay untuk menunjukkan loading
        setTimeout(() => {
            searchLoading.classList.add('hidden');
            renderTable(filteredData);
        }, 1000);
    }

    // Event handler untuk search input dengan debounce 5 detik
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value;

        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Show loading immediately
        searchLoading.classList.remove('hidden');

        // Set new timeout dengan delay 5 detik (5000ms)
        searchTimeout = setTimeout(() => {
            performSearch(searchTerm);
        }, 5000); // 5 detik delay
    });

    // Clear search dan tampilkan semua data
    function clearSearch() {
        searchInput.value = '';
        currentSearchTerm = '';
        searchInfo.classList.add('hidden');
        searchLoading.classList.add('hidden');

        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        renderTable(allData);
    }

    // Enhanced edit function dengan sweet alert
    function editSantri(id) {
        Swal.fire({
            title: 'Membuka Editor',
            text: 'Mengarahkan ke halaman edit...',
            icon: 'info',
            showConfirmButton: false,
            timer: 1500,
            willOpen: () => {
                Swal.showLoading();
            },
            willClose: () => {
                window.location.href = `/santri/${id}/edit`;
            }
        });
    }

    // Enhanced delete function dengan sweet alert
    function deleteSantri(id) {
        Swal.fire({
            title: 'Hapus Santri?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700',
                cancelButton: 'px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus data santri',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/santri/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => {
                        if (response.ok) {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'Data santri berhasil dihapus.',
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                willClose: () => {
                                    location.reload();
                                }
                            });
                        } else {
                            throw new Error('Gagal menghapus data');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus santri.',
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                    });
            }
        });
    }

    // Initialize table
    document.addEventListener('DOMContentLoaded', function() {
        renderTable(allData);
    });
</script>

<!-- Include SweetAlert2 for better alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>