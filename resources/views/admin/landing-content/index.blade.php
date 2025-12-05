@extends('index')

@section('title', 'Kelola Konten Landing Page')

@section('content')
<div class="flex">
    <x-sidemenu title="PesantrenKita" class="h-full min-h-screen" />
    <main class="flex-1 p-4 overflow-y-auto">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="card-title mb-0 fw-bold text-primary">Konten Landing Page</h4>
                                    <p class="text-muted mb-0 small">Kelola semua konten yang ditampilkan di halaman depan</p>
                                </div>
                                <a href="{{ route('admin.landing-content.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                                    <i class="bi bi-plus-circle me-2"></i>Tambah Konten
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Filter Section -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">Filter & Pencarian</h6>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="{{ route('admin.landing-content.index') }}" id="filterForm">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label small text-muted mb-1">Filter Pesantren</label>
                                                <select name="ponpes_id" class="form-select form-select-sm" id="filterPonpes">
                                                    <option value="">Semua Pesantren</option>
                                                    @foreach($ponpesList as $ponpes)
                                                    <option value="{{ $ponpes->id_ponpes }}"
                                                        {{ request('ponpes_id') == $ponpes->id_ponpes ? 'selected' : '' }}>
                                                        {{ $ponpes->nama_ponpes }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label small text-muted mb-1">Filter Tipe Konten</label>
                                                <select name="content_type" class="form-select form-select-sm" id="filterType">
                                                    <option value="">Semua Tipe</option>
                                                    <option value="carousel" {{ request('content_type') == 'carousel' ? 'selected' : '' }}>Carousel</option>
                                                    <option value="about_founder" {{ request('content_type') == 'about_founder' ? 'selected' : '' }}>Founder</option>
                                                    <option value="about_leader" {{ request('content_type') == 'about_leader' ? 'selected' : '' }}>Leader</option>
                                                    <option value="footer" {{ request('content_type') == 'footer' ? 'selected' : '' }}>Footer</option>
                                                    <option value="section_title" {{ request('content_type') == 'section_title' ? 'selected' : '' }}>Section Title</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label small text-muted mb-1">Pencarian</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="search" class="form-control"
                                                        placeholder="Cari judul atau deskripsi..."
                                                        value="{{ request('search') }}"
                                                        id="searchInput">
                                                    <button class="btn btn-outline-primary" type="submit" id="searchBtn">
                                                        <i class="bi bi-search"></i>
                                                    </button>
                                                    @if(request()->hasAny(['ponpes_id', 'content_type', 'search']))
                                                    <a href="{{ route('admin.landing-content.index') }}" class="btn btn-outline-secondary">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Stats Overview -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="stat-card bg-primary bg-opacity-10 p-3 rounded-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-collection text-primary fs-4 me-3"></i>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $contents->total() }}</h6>
                                                    <small class="text-muted">Total Konten</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="stat-card bg-success bg-opacity-10 p-3 rounded-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-check-circle text-success fs-4 me-3"></i>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $contents->where('is_active', true)->count() }}</h6>
                                                    <small class="text-muted">Aktif</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="stat-card bg-info bg-opacity-10 p-3 rounded-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-image text-info fs-4 me-3"></i>
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $contents->whereNotNull('image')->count() }}</h6>
                                                    <small class="text-muted">Memiliki Gambar</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive border rounded-3">
                                <table class="table table-hover mb-0" id="contentTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="60" class="ps-4">#</th>
                                            <th width="80">Preview</th>
                                            <th>Konten</th>
                                            <th width="120">Tipe</th>
                                            <th width="150">Pesantren</th>
                                            <th width="100">Urutan</th>
                                            <th width="100">Status</th>
                                            <th width="150" class="text-end pe-4">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sortable">
                                        @forelse($contents as $content)
                                        <tr data-id="{{ $content->id_content }}" class="sortable-item">
                                            <td class="ps-4 fw-semibold text-muted">
                                                {{ $loop->iteration + (($contents->currentPage() - 1) * $contents->perPage()) }}
                                            </td>
                                            <td>
                                                <div class="image-preview-wrapper">
                                                    @if($content->image)
                                                    <img src="{{ Storage::url($content->image) }}"
                                                        alt="{{ $content->title }}"
                                                        class="img-thumbnail rounded cursor-pointer"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#imageModal"
                                                        data-image="{{ Storage::url($content->image) }}"
                                                        data-title="{{ $content->title }}"
                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                        style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image text-muted fs-5"></i>
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong class="text-truncate" style="max-width: 250px;"
                                                        title="{{ $content->title }}">
                                                        {{ $content->title }}
                                                    </strong>
                                                    @if($content->subtitle)
                                                    <small class="text-muted text-truncate" style="max-width: 250px;">
                                                        {{ Str::limit($content->subtitle, 60) }}
                                                    </small>
                                                    @endif
                                                    <small class="text-primary mt-1">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $content->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                $typeColors = [
                                                'carousel' => 'primary',
                                                'about_founder' => 'success',
                                                'about_leader' => 'info',
                                                'footer' => 'secondary',
                                                'section_title' => 'warning'
                                                ];
                                                $color = $typeColors[$content->content_type] ?? 'dark';
                                                @endphp
                                                <span class="badge bg-{{ $color }} rounded-pill px-3 py-1">
                                                    <i class="bi bi-tag me-1"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $content->content_type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-house-door text-muted me-2"></i>
                                                    <span class="text-truncate" style="max-width: 120px;"
                                                        title="{{ $content->ponpes->nama_ponpes ?? 'N/A' }}">
                                                        {{ $content->ponpes->nama_ponpes ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-sort-numeric-up text-muted me-2"></i>
                                                    <input type="number"
                                                        class="form-control form-control-sm order-input border-0 bg-light rounded-2"
                                                        value="{{ $content->display_order }}"
                                                        data-id="{{ $content->id_content }}"
                                                        style="width: 60px; text-align: center;"
                                                        min="1">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input status-toggle"
                                                            type="checkbox"
                                                            role="switch"
                                                            data-id="{{ $content->id_content }}"
                                                            id="status{{ $content->id_content }}"
                                                            {{ $content->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label small ms-2"
                                                            for="status{{ $content->id_content }}">
                                                            {{ $content->is_active ? 'Aktif' : 'Nonaktif' }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group" role="group" aria-label="Aksi">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-info rounded-start preview-btn"
                                                        title="Preview" data-bs-toggle="tooltip"
                                                        data-id="{{ $content->id_content }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <a href="{{ route('admin.landing-content.edit', $content->id_content) }}"
                                                        class="btn btn-sm btn-outline-warning"
                                                        title="Edit" data-bs-toggle="tooltip">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger rounded-end delete-btn"
                                                        data-id="{{ $content->id_content }}"
                                                        data-title="{{ $content->title }}"
                                                        title="Hapus" data-bs-toggle="tooltip">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-inbox display-4 text-muted"></i>
                                                    <h5 class="mt-3 text-muted">Belum ada konten</h5>
                                                    <p class="text-muted mb-4">Mulai dengan menambahkan konten pertama Anda</p>
                                                    <a href="{{ route('admin.landing-content.create') }}" class="btn btn-primary">
                                                        <i class="bi bi-plus-circle me-2"></i>Tambah Konten
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($contents->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Menampilkan <strong>{{ $contents->firstItem() }} - {{ $contents->lastItem() }}</strong>
                                    dari <strong>{{ $contents->total() }}</strong> konten
                                </div>
                                <nav aria-label="Page navigation">
                                    {{ $contents->withQueryString()->onEachSide(1)->links() }}
                                </nav>
                            </div>
                            @endif
                        </div>

                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-success btn-sm d-flex align-items-center" id="saveOrder">
                                        <i class="bi bi-save me-2"></i>Simpan Urutan
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center"
                                        id="resetOrder" title="Reset ke urutan awal">
                                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                                    </button>
                                </div>
                                <div>
                                    <small class="text-muted">
                                        <i class="bi bi-arrows-move me-1"></i>
                                        Drag & drop baris untuk mengubah urutan
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview Gambar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" class="img-fluid rounded" alt="">
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Preview Modal -->
        <div class="modal fade" id="detailPreviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalTitle">
                            <i class="bi bi-eye me-2"></i>Preview Konten
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Thumbnail</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <img id="detailImage" src="" class="img-fluid rounded mb-3"
                                            style="max-height: 200px; display: none;">
                                        <div id="noImageDetail" class="text-muted">
                                            <i class="bi bi-image display-6"></i>
                                            <p class="mt-2 small">Tidak ada gambar</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border mt-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Info Konten</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <td width="120"><strong>Tipe:</strong></td>
                                                <td>
                                                    <span id="detailType" class="badge"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pesantren:</strong></td>
                                                <td id="detailPonpes"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    <span id="detailStatus" class="badge"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Urutan:</strong></td>
                                                <td id="detailOrder"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Dibuat:</strong></td>
                                                <td id="detailCreated"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Diperbarui:</strong></td>
                                                <td id="detailUpdated"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card border h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Detail Konten</h6>
                                    </div>
                                    <div class="card-body">
                                        <h4 id="detailTitle" class="fw-bold mb-3"></h4>

                                        <div class="mb-4">
                                            <h6 class="text-muted mb-2">Subjudul:</h6>
                                            <p id="detailSubtitle" class="lead"></p>
                                        </div>

                                        <div class="mb-4">
                                            <h6 class="text-muted mb-2">Jabatan/Posisi:</h6>
                                            <p id="detailPosition" class="fw-semibold"></p>
                                        </div>

                                        <div class="mb-4">
                                            <h6 class="text-muted mb-2">Deskripsi:</h6>
                                            <div id="detailDescription" class="bg-light p-3 rounded"></div>
                                        </div>

                                        <div class="mb-4">
                                            <h6 class="text-muted mb-2">URL/Link:</h6>
                                            <a id="detailUrl" href="#" target="_blank" class="d-block text-truncate" style="display: none;">
                                                <i class="bi bi-link-45deg"></i>
                                                <span id="urlText"></span>
                                            </a>
                                            <small id="noUrlDetail" class="text-muted">Tidak ada URL</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a id="editBtn" href="#" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <a id="fullViewBtn" href="#" class="btn btn-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus konten <strong id="deleteContentTitle"></strong>?</p>
                        <p class="text-muted small">Aksi ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form id="deleteForm" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endsection

    </main>
</div>
    @push('styles')
    <style>
        .stat-card {
            transition: transform 0.2s;
            min-width: 180px;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .image-preview-wrapper img {
            transition: transform 0.2s;
            cursor: pointer;
        }

        .image-preview-wrapper img:hover {
            transform: scale(1.05);
        }

        .sortable-item {
            cursor: move;
        }

        .sortable-item.ui-sortable-helper {
            background-color: #f8f9fa;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .empty-state {
            opacity: 0.7;
        }

        .order-input:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
        }

        .badge {
            font-weight: 500;
        }

        /* Content type colors */
        .content-type-carousel {
            background-color: #e3f2fd !important;
            color: #0d6efd !important;
        }

        .content-type-founder {
            background-color: #e8f5e9 !important;
            color: #198754 !important;
        }

        .content-type-leader {
            background-color: #e3f2fd !important;
            color: #0dcaf0 !important;
        }

        .content-type-footer {
            background-color: #e2e3e5 !important;
            color: #6c757d !important;
        }

        .content-type-section {
            background-color: #fff3cd !important;
            color: #ffc107 !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

            // Quick Preview - Modal
            $('.preview-btn').click(function(e) {
                e.preventDefault();
                e.stopPropagation();

                const contentId = $(this).data('id');
                loadContentDetail(contentId);
            });

            function loadContentDetail(contentId) {
                $.ajax({
                    url: '/admin/landing-content/' + contentId + '/detail',
                    method: 'GET',
                    dataType: 'json',
                    success: function(content) {
                        populateDetailModal(content);
                        $('#detailPreviewModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading content:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Tidak dapat memuat detail konten'
                        });
                    }
                });
            }

            function populateDetailModal(content) {
                // Set modal title
                $('#detailModalTitle').html(`<i class="bi bi-${getTypeIcon(content.content_type)} me-2"></i>Preview: ${content.title || 'Tanpa Judul'}`);

                // Set image
                if (content.image_url) {
                    $('#detailImage').attr('src', content.image_url).show();
                    $('#noImageDetail').hide();
                } else {
                    $('#detailImage').hide();
                    $('#noImageDetail').show();
                }

                // Set type badge
                const typeColors = {
                    'carousel': 'primary',
                    'about_founder': 'success',
                    'about_leader': 'info',
                    'footer': 'secondary',
                    'section_title': 'warning'
                };
                const color = typeColors[content.content_type] || 'dark';
                $('#detailType').attr('class', `badge bg-${color}`).text(content.content_type.replace('_', ' '));

                // Set other info
                $('#detailPonpes').text(content.ponpes ? content.ponpes.nama_ponpes : 'Tidak ada pesantren');
                $('#detailStatus').attr('class', `badge ${content.is_active ? 'bg-success' : 'bg-danger'}`)
                    .text(content.is_active ? 'Aktif' : 'Nonaktif');
                $('#detailOrder').text(content.display_order);
                $('#detailCreated').text(content.created_at);
                $('#detailUpdated').text(content.updated_at_formatted);

                // Set content details
                $('#detailTitle').text(content.title || 'Tanpa Judul');
                $('#detailSubtitle').text(content.subtitle || 'Tidak ada subjudul');
                $('#detailPosition').text(content.position || 'Tidak ada jabatan/posisi');

                // Handle description
                if (content.description) {
                    $('#detailDescription').html(content.description.replace(/\n/g, '<br>'));
                } else {
                    $('#detailDescription').html('<em class="text-muted">Tidak ada deskripsi</em>');
                }

                // Set URL
                if (content.url) {
                    $('#detailUrl').attr('href', content.url).show();
                    $('#urlText').text(content.url);
                    $('#noUrlDetail').hide();
                } else {
                    $('#detailUrl').hide();
                    $('#noUrlDetail').show();
                }

                // Set action buttons
                $('#editBtn').attr('href', '/admin/landing-content/' + content.id_content + '/edit');
                $('#fullViewBtn').attr('href', '/admin/landing-content/' + content.id_content);
            }

            function getTypeIcon(type) {
                const icons = {
                    'carousel': 'images',
                    'about_founder': 'person-badge',
                    'about_leader': 'person-square',
                    'footer': 'link-45deg',
                    'section_title': 'card-heading'
                };
                return icons[type] || 'file-earmark';
            }

            // Filter handling
            $('#filterPonpes, #filterType').change(function() {
                $('#filterForm').submit();
            });

            $('#searchInput').keypress(function(e) {
                if (e.which === 13) {
                    $('#filterForm').submit();
                }
            });

            // Status toggle
            $('.status-toggle').change(function() {
                const contentId = $(this).data('id');
                const isActive = $(this).is(':checked') ? 1 : 0;
                const label = $(this).siblings('label');

                $.ajax({
                    url: '/admin/landing-content/' + contentId + '/toggle-status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_active: isActive,
                        _method: 'PATCH'
                    },
                    beforeSend: function() {
                        label.text('Memproses...');
                    },
                    success: function(response) {
                        label.text(isActive ? 'Aktif' : 'Nonaktif');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Status konten berhasil diubah',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat mengubah status'
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });

            // Update order with debounce
            let orderTimeout;
            $('.order-input').on('input', function() {
                clearTimeout(orderTimeout);
                orderTimeout = setTimeout(() => {
                    const contentId = $(this).data('id');
                    const order = $(this).val();

                    $.ajax({
                        url: '/admin/landing-content/' + contentId + '/update-order',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            display_order: order,
                            _method: 'PATCH'
                        }
                    });
                }, 500);
            });

            // Save all orders
            $('#saveOrder').click(function() {
                const orders = [];
                $('.order-input').each(function() {
                    orders.push({
                        id: $(this).data('id'),
                        order: $(this).val()
                    });
                });

                $(this).prop('disabled', true).html('<i class="bi bi-hourglass-split me-2"></i>Menyimpan...');

                $.ajax({
                    url: '/admin/landing-content/update-order',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        items: orders
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Urutan berhasil disimpan',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan urutan'
                        });
                        $('#saveOrder').prop('disabled', false).html('<i class="bi bi-save me-2"></i>Simpan Urutan');
                    }
                });
            });

            // Reset order
            $('#resetOrder').click(function() {
                $('.order-input').each(function(index) {
                    $(this).val(index + 1);
                });

                Swal.fire({
                    icon: 'info',
                    title: 'Urutan direset',
                    text: 'Klik "Simpan Urutan" untuk menerapkan perubahan',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            // Make table rows sortable
            $("#sortable").sortable({
                handle: ".sortable-item",
                items: ".sortable-item",
                cursor: "move",
                opacity: 0.7,
                placeholder: "sortable-placeholder",
                update: function(event, ui) {
                    updateOrderNumbers();
                }
            }).disableSelection();

            function updateOrderNumbers() {
                $("#sortable tr.sortable-item").each(function(index) {
                    const orderInput = $(this).find('.order-input');
                    orderInput.val(index + 1);
                });
            }

            // Image modal
            $('#imageModal').on('show.bs.modal', function(event) {
                const button = $(event.relatedTarget);
                const imageUrl = button.data('image');
                const title = button.data('title');

                $(this).find('#modalImage').attr('src', imageUrl).attr('alt', title);
                $(this).find('.modal-title').text('Preview: ' + title);
            });

            // Delete confirmation
            $('.delete-btn').click(function() {
                const contentId = $(this).data('id');
                const contentTitle = $(this).data('title');
                const deleteUrl = '/admin/landing-content/' + contentId;

                $('#deleteContentTitle').text(contentTitle);
                $('#deleteForm').attr('action', deleteUrl);
                $('#deleteModal').modal('show');
            });
        });
    </script>
    @endpush