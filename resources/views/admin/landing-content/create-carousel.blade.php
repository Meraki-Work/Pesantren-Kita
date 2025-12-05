@extends('layouts.admin')

@section('title', 'Tambah Carousel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0 fw-bold text-primary">
                                <i class="bi bi-images me-2"></i>Tambah Carousel
                            </h4>
                            <p class="text-muted mb-0 small">Gambar slider utama di halaman depan</p>
                        </div>
                        <a href="{{ route('admin.landing-content.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Hidden field untuk content_type -->
                        <input type="hidden" name="content_type" value="carousel">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Informasi Carousel</h6>
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
                                            <label for="title" class="form-label">Judul Carousel</label>
                                            <input type="text" name="title" id="title" 
                                                   class="form-control @error('title') is-invalid @enderror" 
                                                   value="{{ old('title') }}" 
                                                   placeholder="Contoh: Selamat Datang di Pesantren Al-Ikhlas">
                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="subtitle" class="form-label">Subjudul</label>
                                            <textarea name="subtitle" id="subtitle" 
                                                      class="form-control @error('subtitle') is-invalid @enderror" 
                                                      rows="2" 
                                                      placeholder="Deskripsi singkat">{{ old('subtitle') }}</textarea>
                                            @error('subtitle')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Deskripsi Lengkap</label>
                                            <textarea name="description" id="description" 
                                                      class="form-control @error('description') is-invalid @enderror" 
                                                      rows="4" 
                                                      placeholder="Deskripsi detail tentang pesantren">{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="url" class="form-label">Link Tujuan (Opsional)</label>
                                                    <input type="url" name="url" id="url" 
                                                           class="form-control @error('url') is-invalid @enderror" 
                                                           value="{{ old('url') }}" 
                                                           placeholder="https://example.com">
                                                    @error('url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                                    <small class="text-muted">Angka kecil muncul lebih dulu</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Image Upload Card -->
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Gambar Carousel <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload Gambar</label>
                                            <input type="file" name="image" id="image" 
                                                   class="form-control @error('image') is-invalid @enderror" 
                                                   accept="image/*" required>
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted d-block mt-1">
                                                Format: JPG, PNG, GIF, WEBP<br>
                                                Maksimal: 2MB<br>
                                                Ukuran disarankan: 1280×720 px
                                            </small>
                                        </div>
                                        
                                        <!-- Image Preview -->
                                        <div class="text-center mt-3">
                                            <div id="imagePreview" class="mb-3" style="display: none;">
                                                <img id="previewImage" class="img-fluid rounded border" 
                                                     style="max-height: 200px;">
                                            </div>
                                            <div id="imagePlaceholder" class="text-center p-4 border rounded bg-light">
                                                <i class="bi bi-image text-muted display-4"></i>
                                                <p class="mt-2 text-muted small">Preview gambar akan muncul di sini</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Status Toggle -->
                                        <div class="mt-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="is_active" id="is_active" value="1" 
                                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    <span class="fw-semibold">Aktifkan Carousel</span>
                                                </label>
                                            </div>
                                            <small class="text-muted">Nonaktifkan untuk menyembunyikan carousel</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Preview Tips -->
                                <div class="card border mt-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Tips Carousel</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0 small">
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                Gunakan gambar berkualitas tinggi
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                Judul harus menarik perhatian
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                Maksimal 5-7 carousel per pesantren
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                Atur urutan yang logis
                                            </li>
                                            <li>
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                Pastikan konten relevan
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.landing-content.create') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Pilihan
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Carousel
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
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('previewImage');
    const previewContainer = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
            placeholder.style.display = 'none';
        }
        
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
        placeholder.style.display = 'block';
    }
});
</script>
@endpush