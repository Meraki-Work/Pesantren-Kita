@extends('layouts.admin')

@section('title', 'Tambah Founder/Pendiri')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0 fw-bold text-success">
                                <i class="bi bi-person-badge me-2"></i>Tambah Founder/Pendiri
                            </h4>
                            <p class="text-muted mb-0 small">Profil pendiri pesantren</p>
                        </div>
                        <a href="{{ route('admin.landing-content.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="content_type" value="about_founder">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Image Upload Card -->
                                <div class="card border h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Foto Founder <span class="text-danger">*</span></h6>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload Foto</label>
                                            <input type="file" name="image" id="image" 
                                                   class="form-control @error('image') is-invalid @enderror" 
                                                   accept="image/*" required>
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted d-block mt-1">
                                                Format: JPG, PNG, GIF, WEBP<br>
                                                Maksimal: 2MB<br>
                                                Ukuran disarankan: 400×500 px
                                            </small>
                                        </div>
                                        
                                        <!-- Image Preview -->
                                        <div class="text-center mt-3 flex-grow-1">
                                            <div id="imagePreview" class="mb-3" style="display: none;">
                                                <img id="previewImage" class="img-fluid rounded border" 
                                                     style="max-height: 300px;">
                                            </div>
                                            <div id="imagePlaceholder" class="text-center p-4 border rounded bg-light h-100">
                                                <i class="bi bi-person-square text-muted display-4"></i>
                                                <p class="mt-2 text-muted small">Foto founder akan muncul di sini</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="card border h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Informasi Founder</h6>
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
                                                    <label for="title" class="form-label">Nama Founder <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" id="title" 
                                                           class="form-control @error('title') is-invalid @enderror" 
                                                           value="{{ old('title') }}" 
                                                           placeholder="Contoh: KH. Ahmad Dahlan" required>
                                                    @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="position" class="form-label">Jabatan/Posisi <span class="text-danger">*</span></label>
                                                    <input type="text" name="position" id="position" 
                                                           class="form-control @error('position') is-invalid @enderror" 
                                                           value="{{ old('position') }}" 
                                                           placeholder="Contoh: Pendiri Pesantren" required>
                                                    @error('position')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Biografi <span class="text-danger">*</span></label>
                                            <textarea name="description" id="description" 
                                                      class="form-control @error('description') is-invalid @enderror" 
                                                      rows="6" 
                                                      placeholder="Tuliskan biografi lengkap founder..." required>{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="url" class="form-label">Link Sosial Media (Opsional)</label>
                                                    <input type="url" name="url" id="url" 
                                                           class="form-control @error('url') is-invalid @enderror" 
                                                           value="{{ old('url') }}" 
                                                           placeholder="https://instagram.com/username">
                                                    @error('url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Link ke profil sosial media founder</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="display_order" class="form-label">Urutan Tampilan</label>
                                                    <input type="number" name="display_order" id="display_order" 
                                                           class="form-control @error('display_order') is-invalid @enderror" 
                                                           value="{{ old('display_order', 0) }}" 
                                                           min="0">
                                                    @error('display_order')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Atur urutan jika ada beberapa founder</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="is_active" id="is_active" value="1" 
                                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    <span class="fw-semibold">Tampilkan di Halaman</span>
                                                </label>
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
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i>Simpan Founder
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