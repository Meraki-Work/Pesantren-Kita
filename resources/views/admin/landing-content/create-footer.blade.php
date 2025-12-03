@extends('layouts.admin')

@section('title', 'Tambah Footer Link')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0 fw-bold text-secondary">
                                <i class="bi bi-link-45deg me-2"></i>Tambah Footer Link
                            </h4>
                            <p class="text-muted mb-0 small">Link penting di bagian footer website</p>
                        </div>
                        <a href="{{ route('admin.landing-content.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="content_type" value="footer">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Informasi Link</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="ponpes_id" class="form-label">Pesantren <span class="text-danger">*</span></label>
                                            <select name="ponpes_id" id="ponpes_id" class="form-select @error('ponpes_id') is-invalid @enderror" required>
                                                <option value="">Pilih Pesantren</option>
                                                @foreach($ponpesList as $ponpes)
                                                <option value="{{ $ponpes->id_ponpes }}" 
                                                        {{ old('ponpes_id') == $ponpes->id_ponpes ? 'selected' : '' }}>
                                                    {{ $ponpes->nama_ponpes }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('ponpes_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="title" class="form-label">Judul Link <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" id="title" 
                                                           class="form-control @error('title') is-invalid @enderror" 
                                                           value="{{ old('title') }}" 
                                                           placeholder="Contoh: Instagram Pesantren" required>
                                                    @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="position" class="form-label">Posisi/Kategori</label>
                                                    <select name="position" id="position" 
                                                            class="form-select @error('position') is-invalid @enderror">
                                                        <option value="">Pilih Kategori</option>
                                                        <option value="social" {{ old('position') == 'social' ? 'selected' : '' }}>Sosial Media</option>
                                                        <option value="contact" {{ old('position') == 'contact' ? 'selected' : '' }}>Kontak</option>
                                                        <option value="quick_link" {{ old('position') == 'quick_link' ? 'selected' : '' }}>Quick Link</option>
                                                        <option value="legal" {{ old('position') == 'legal' ? 'selected' : '' }}>Legal</option>
                                                        <option value="other" {{ old('position') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                                    </select>
                                                    @error('position')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                            <textarea name="description" id="description" 
                                                      class="form-control @error('description') is-invalid @enderror" 
                                                      rows="3" 
                                                      placeholder="Deskripsi singkat link..." required>{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Contoh: Ikuti kami di Instagram untuk update terbaru</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="url" class="form-label">URL Link <span class="text-danger">*</span></label>
                                            <input type="url" name="url" id="url" 
                                                   class="form-control @error('url') is-invalid @enderror" 
                                                   value="{{ old('url') }}" 
                                                   placeholder="https://instagram.com/pesantren_alkhair" required>
                                            @error('url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Pastikan URL valid dan lengkap dengan https://</small>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="display_order" class="form-label">Urutan Tampilan <span class="text-danger">*</span></label>
                                                    <input type="number" name="display_order" id="display_order" 
                                                           class="form-control @error('display_order') is-invalid @enderror" 
                                                           value="{{ old('display_order', 1) }}" 
                                                           min="1" required>
                                                    @error('display_order')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Atur urutan tampil di footer</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Ikon (Opsional)</label>
                                                    <select class="form-select" name="icon">
                                                        <option value="">Pilih Ikon</option>
                                                        <option value="bi-instagram" {{ old('icon') == 'bi-instagram' ? 'selected' : '' }}>Instagram</option>
                                                        <option value="bi-facebook" {{ old('icon') == 'bi-facebook' ? 'selected' : '' }}>Facebook</option>
                                                        <option value="bi-twitter" {{ old('icon') == 'bi-twitter' ? 'selected' : '' }}>Twitter/X</option>
                                                        <option value="bi-youtube" {{ old('icon') == 'bi-youtube' ? 'selected' : '' }}>YouTube</option>
                                                        <option value="bi-whatsapp" {{ old('icon') == 'bi-whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                                        <option value="bi-envelope" {{ old('icon') == 'bi-envelope' ? 'selected' : '' }}>Email</option>
                                                        <option value="bi-telephone" {{ old('icon') == 'bi-telephone' ? 'selected' : '' }}>Telepon</option>
                                                        <option value="bi-geo-alt" {{ old('icon') == 'bi-geo-alt' ? 'selected' : '' }}>Lokasi</option>
                                                        <option value="bi-link" {{ old('icon') == 'bi-link' ? 'selected' : '' }}>Link Umum</option>
                                                    </select>
                                                    <small class="text-muted">Ikon akan ditampilkan di samping link</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="is_active" id="is_active" value="1" 
                                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    <span class="fw-semibold">Aktifkan Link</span>
                                                </label>
                                            </div>
                                            <small class="text-muted">Nonaktifkan untuk menyembunyikan link dari footer</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Preview Card -->
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Preview Footer</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <h6 class="fw-bold">Contoh Footer Link:</h6>
                                            <div class="list-group">
                                                <a href="#" class="list-group-item list-group-item-action">
                                                    <i class="bi bi-instagram me-2 text-danger"></i>
                                                    <span>Instagram Pesantren</span>
                                                    <small class="text-muted d-block">Ikuti update terbaru</small>
                                                </a>
                                                <a href="#" class="list-group-item list-group-item-action">
                                                    <i class="bi bi-whatsapp me-2 text-success"></i>
                                                    <span>WhatsApp Kontak</span>
                                                    <small class="text-muted d-block">Hubungi kami via WhatsApp</small>
                                                </a>
                                                <a href="#" class="list-group-item list-group-item-action">
                                                    <i class="bi bi-envelope me-2 text-primary"></i>
                                                    <span>Email Resmi</span>
                                                    <small class="text-muted d-block">Kirim email ke kami</small>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-info bg-info bg-opacity-10 border-info">
                                            <h6 class="alert-heading fw-bold">Tips Footer Link:</h6>
                                            <ul class="mb-0 small">
                                                <li>Gunakan ikon yang sesuai dengan platform</li>
                                                <li>Pastikan link aktif dan dapat diakses</li>
                                                <li>Deskripsi harus jelas dan informatif</li>
                                                <li>Kelompokkan link berdasarkan kategori</li>
                                                <li>Maksimal 8-10 link per pesantren</li>
                                            </ul>
                                        </div>
                                        
                                        <!-- Quick Links -->
                                        <div class="mt-3">
                                            <h6 class="fw-bold">Link Populer:</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="badge bg-light border text-dark">Instagram</span>
                                                <span class="badge bg-light border text-dark">Facebook</span>
                                                <span class="badge bg-light border text-dark">YouTube</span>
                                                <span class="badge bg-light border text-dark">WhatsApp</span>
                                                <span class="badge bg-light border text-dark">Email</span>
                                                <span class="badge bg-light border text-dark">Kontak</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.landing-content.create') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Pilihan
                            </a>
                            <button type="submit" class="btn btn-secondary">
                                <i class="bi bi-save me-1"></i>Simpan Footer Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection