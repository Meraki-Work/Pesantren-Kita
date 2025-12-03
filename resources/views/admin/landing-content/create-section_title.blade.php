@extends('layouts.admin')

@section('title', 'Tambah Section Title')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0 fw-bold text-warning">
                                <i class="bi bi-card-heading me-2"></i>Tambah Section Title
                            </h4>
                            <p class="text-muted mb-0 small">Judul dan deskripsi untuk setiap bagian halaman</p>
                        </div>
                        <a href="{{ route('admin.landing-content.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="content_type" value="section_title">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Informasi Section</h6>
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
                                        
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Judul Section <span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="title" 
                                                   class="form-control @error('title') is-invalid @enderror" 
                                                   value="{{ old('title') }}" 
                                                   placeholder="Contoh: Tentang Pesantren" required>
                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="subtitle" class="form-label">Subjudul/Deskripsi Singkat</label>
                                            <textarea name="subtitle" id="subtitle" 
                                                      class="form-control @error('subtitle') is-invalid @enderror" 
                                                      rows="2" 
                                                      placeholder="Deskripsi singkat tentang section">{{ old('subtitle') }}</textarea>
                                            @error('subtitle')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="position" class="form-label">Posisi/Section ID</label>
                                                    <select name="position" id="position" 
                                                            class="form-select @error('position') is-invalid @enderror">
                                                        <option value="">Pilih Posisi</option>
                                                        <option value="about" {{ old('position') == 'about' ? 'selected' : '' }}>Tentang Kami</option>
                                                        <option value="program" {{ old('position') == 'program' ? 'selected' : '' }}>Program</option>
                                                        <option value="facility" {{ old('position') == 'facility' ? 'selected' : '' }}>Fasilitas</option>
                                                        <option value="founder" {{ old('position') == 'founder' ? 'selected' : '' }}>Founder</option>
                                                        <option value="leader" {{ old('position') == 'leader' ? 'selected' : '' }}>Pengurus</option>
                                                        <option value="testimonial" {{ old('position') == 'testimonial' ? 'selected' : '' }}>Testimoni</option>
                                                        <option value="gallery" {{ old('position') == 'gallery' ? 'selected' : '' }}>Galeri</option>
                                                        <option value="contact" {{ old('position') == 'contact' ? 'selected' : '' }}>Kontak</option>
                                                        <option value="header" {{ old('position') == 'header' ? 'selected' : '' }}>Header</option>
                                                        <option value="hero" {{ old('position') == 'hero' ? 'selected' : '' }}>Hero Section</option>
                                                        <option value="custom" {{ old('position') == 'custom' ? 'selected' : '' }}>Custom</option>
                                                    </select>
                                                    @error('position')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Untuk mengelompokkan section yang sama</small>
                                                </div>
                                            </div>
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
                                                    <small class="text-muted">Atur urutan munculnya section di halaman</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Konten Tambahan (Opsional)</label>
                                            <textarea name="description" id="description" 
                                                      class="form-control @error('description') is-invalid @enderror" 
                                                      rows="4" 
                                                      placeholder="Konten tambahan untuk section">{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Untuk konten yang lebih panjang di bawah judul</small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="is_active" id="is_active" value="1" 
                                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    <span class="fw-semibold">Tampilkan Section</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Preview Card -->
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Preview Section</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="section-preview text-center p-4">
                                            <h3 id="previewTitle" class="fw-bold mb-3 text-primary">
                                                {{ old('title', 'Tentang Pesantren') }}
                                            </h3>
                                            <p id="previewSubtitle" class="lead text-muted mb-4">
                                                {{ old('subtitle', 'Deskripsi singkat tentang section ini') }}
                                            </p>
                                            <div class="divider mx-auto" style="width: 100px; height: 3px; background: linear-gradient(90deg, #ffc107, #ff9800);"></div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <h6 class="fw-bold">Contoh Section:</h6>
                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>Tentang Kami</strong>
                                                            <small class="d-block text-muted">position: about</small>
                                                        </div>
                                                        <span class="badge bg-warning">#1</span>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>Program Unggulan</strong>
                                                            <small class="d-block text-muted">position: program</small>
                                                        </div>
                                                        <span class="badge bg-warning">#2</span>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>Fasilitas</strong>
                                                            <small class="d-block text-muted">position: facility</small>
                                                        </div>
                                                        <span class="badge bg-warning">#3</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-warning bg-warning bg-opacity-10 border-warning mt-3">
                                            <h6 class="alert-heading fw-bold">Tips Section Title:</h6>
                                            <ul class="mb-0 small">
                                                <li>Judul harus jelas dan deskriptif</li>
                                                <li>Gunakan subjudul untuk penjelasan tambahan</li>
                                                <li>Atur position untuk grouping yang tepat</li>
                                                <li>Urutan menentukan posisi di halaman</li>
                                                <li>Gunakan konten tambahan jika diperlukan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.landing-content.create') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Pilihan
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-1"></i>Simpan Section Title
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Live preview untuk title dan subtitle
document.getElementById('title').addEventListener('input', function(e) {
    document.getElementById('previewTitle').textContent = e.target.value || 'Tentang Pesantren';
});

document.getElementById('subtitle').addEventListener('input', function(e) {
    document.getElementById('previewSubtitle').textContent = e.target.value || 'Deskripsi singkat tentang section ini';
});
</script>

@push('styles')
<style>
.section-preview {
    background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
    border-radius: 10px;
    border: 1px solid #ffd54f;
}
.divider {
    border-radius: 2px;
}
</style>
@endpush