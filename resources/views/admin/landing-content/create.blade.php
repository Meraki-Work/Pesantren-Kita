@extends('layouts.admin')

@section('title', 'Tambah Konten Landing Page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0 fw-bold text-primary">Pilih Tipe Konten</h4>
                            <p class="text-muted mb-0 small">Pilih jenis konten yang ingin ditambahkan</p>
                        </div>
                        <a href="{{ route('admin.landing-content.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Carousel Card -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 80px; height: 80px;">
                                        <i class="bi bi-images text-primary fs-3"></i>
                                    </div>
                                    <h5 class="card-title fw-bold mb-2">Carousel</h5>
                                    <p class="card-text text-muted small mb-4">
                                        Gambar slider utama di halaman depan dengan judul dan deskripsi
                                    </p>
                                    <div class="badge bg-primary rounded-pill px-3 mb-3">Gambar Utama</div>
                                    <div class="mt-auto">
                                        <a href="{{ route('admin.landing-content.create-type', 'carousel') }}" 
                                           class="btn btn-primary w-100">
                                            <i class="bi bi-plus-circle me-2"></i>Tambah Carousel
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top py-3">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Direkomendasikan: 1280×720 px
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- About Founder Card -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 80px; height: 80px;">
                                        <i class="bi bi-person-badge text-success fs-3"></i>
                                    </div>
                                    <h5 class="card-title fw-bold mb-2">Founder/Pendiri</h5>
                                    <p class="card-text text-muted small mb-4">
                                        Profil pendiri pesantren dengan foto dan biografi
                                    </p>
                                    <div class="badge bg-success rounded-pill px-3 mb-3">Profil</div>
                                    <div class="mt-auto">
                                        <a href="{{ route('admin.landing-content.create-type', 'about_founder') }}" 
                                           class="btn btn-success w-100">
                                            <i class="bi bi-plus-circle me-2"></i>Tambah Founder
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top py-3">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Ukuran gambar: 400×500 px
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- About Leader Card -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 80px; height: 80px;">
                                        <i class="bi bi-person-square text-info fs-3"></i>
                                    </div>
                                    <h5 class="card-title fw-bold mb-2">Leader/Pengurus</h5>
                                    <p class="card-text text-muted small mb-4">
                                        Profil pengurus pesantren (ketua, sekretaris, bendahara)
                                    </p>
                                    <div class="badge bg-info rounded-pill px-3 mb-3">Profil</div>
                                    <div class="mt-auto">
                                        <a href="{{ route('admin.landing-content.create-type', 'about_leader') }}" 
                                           class="btn btn-info w-100">
                                            <i class="bi bi-plus-circle me-2"></i>Tambah Leader
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top py-3">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Ukuran gambar: 400×500 px
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Card -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 80px; height: 80px;">
                                        <i class="bi bi-link-45deg text-secondary fs-3"></i>
                                    </div>
                                    <h5 class="card-title fw-bold mb-2">Footer Link</h5>
                                    <p class="card-text text-muted small mb-4">
                                        Link penting di bagian footer (sosial media, kontak, dll)
                                    </p>
                                    <div class="badge bg-secondary rounded-pill px-3 mb-3">Link</div>
                                    <div class="mt-auto">
                                        <a href="{{ route('admin.landing-content.create-type', 'footer') }}" 
                                           class="btn btn-secondary w-100">
                                            <i class="bi bi-plus-circle me-2"></i>Tambah Footer Link
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top py-3">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Ikon tersedia untuk platform populer
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Section Title Card -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 80px; height: 80px;">
                                        <i class="bi bi-card-heading text-warning fs-3"></i>
                                    </div>
                                    <h5 class="card-title fw-bold mb-2">Section Title</h5>
                                    <p class="card-text text-muted small mb-4">
                                        Judul dan deskripsi untuk setiap bagian/section halaman
                                    </p>
                                    <div class="badge bg-warning rounded-pill px-3 mb-3">Judul</div>
                                    <div class="mt-auto">
                                        <a href="{{ route('admin.landing-content.create-type', 'section_title') }}" 
                                           class="btn btn-warning w-100">
                                            <i class="bi bi-plus-circle me-2"></i>Tambah Section Title
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-top py-3">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Untuk mengatur tampilan heading section
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Tips -->
                        <div class="col-md-12 mt-4">
                            <div class="alert alert-info bg-info bg-opacity-10 border-info">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="bi bi-lightbulb fs-3 text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading fw-bold mb-2">Tips Membuat Konten</h6>
                                        <ul class="mb-0">
                                            <li>Pastikan gambar memiliki resolusi yang tepat</li>
                                            <li>Gunakan deskripsi yang informatif dan menarik</li>
                                            <li>Atur urutan tampilan sesuai kebutuhan</li>
                                            <li>Untuk carousel, maksimal 5-7 slide</li>
                                            <li>Gunakan konten yang relevan dengan pesantren</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hover-lift {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.icon-wrapper {
    transition: transform 0.3s;
}
.hover-lift:hover .icon-wrapper {
    transform: scale(1.1);
}
.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}
</style>
@endpush