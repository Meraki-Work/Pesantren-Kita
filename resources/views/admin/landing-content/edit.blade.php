@extends('layouts.admin')

@section('title', 'Edit Konten Landing Page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Konten Landing Page</h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.landing-content.update', $landingContent->id_content) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ponpes_id" class="form-label">Pesantren <span class="text-danger">*</span></label>
                                    <select name="ponpes_id" id="ponpes_id" class="form-select @error('ponpes_id') is-invalid @enderror" required>
                                        <option value="">Pilih Pesantren</option>
                                        @foreach($ponpesList as $ponpes)
                                        <option value="{{ $ponpes->id_ponpes }}" 
                                                {{ old('ponpes_id', $landingContent->ponpes_id) == $ponpes->id_ponpes ? 'selected' : '' }}>
                                            {{ $ponpes->nama_ponpes }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('ponpes_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content_type" class="form-label">Tipe Konten <span class="text-danger">*</span></label>
                                    <select name="content_type" id="content_type" class="form-select @error('content_type') is-invalid @enderror" required>
                                        <option value="">Pilih Tipe Konten</option>
                                        @foreach($contentTypes as $value => $label)
                                        <option value="{{ $value }}" 
                                                {{ old('content_type', $landingContent->content_type) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('content_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul</label>
                                    <input type="text" name="title" id="title" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title', $landingContent->title) }}" 
                                           placeholder="Masukkan judul konten">
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="position" class="form-label">Posisi/Jabatan</label>
                                    <input type="text" name="position" id="position" 
                                           class="form-control @error('position') is-invalid @enderror" 
                                           value="{{ old('position', $landingContent->position) }}" 
                                           placeholder="Contoh: Founder, Ketua Yayasan">
                                    @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subjudul</label>
                            <textarea name="subtitle" id="subtitle" 
                                      class="form-control @error('subtitle') is-invalid @enderror" 
                                      rows="2">{{ old('subtitle', $landingContent->subtitle) }}</textarea>
                            @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea name="description" id="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="4">{{ old('description', $landingContent->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Gambar</label>
                                    
                                    @if($landingContent->image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($landingContent->image) }}" 
                                             alt="Current Image" 
                                             class="img-thumbnail" 
                                             style="max-width: 200px;">
                                        <div class="mt-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="remove_image" id="remove_image" value="1">
                                                <label class="form-check-label text-danger" for="remove_image">
                                                    Hapus gambar ini
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <input type="file" name="image" id="image" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           accept="image/*">
                                    @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                                    
                                    <!-- Image Preview -->
                                    <div id="imagePreview" class="mt-2" style="display: none;">
                                        <img id="previewImage" class="img-thumbnail" 
                                             style="max-width: 200px;">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="url" class="form-label">URL/Link</label>
                                    <input type="url" name="url" id="url" 
                                           class="form-control @error('url') is-invalid @enderror" 
                                           value="{{ old('url', $landingContent->url) }}">
                                    @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="display_order" class="form-label">Urutan Tampilan</label>
                                    <input type="number" name="display_order" id="display_order" 
                                           class="form-control @error('display_order') is-invalid @enderror" 
                                           value="{{ old('display_order', $landingContent->display_order) }}" 
                                           min="0">
                                    @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       name="is_active" id="is_active" value="1" 
                                       {{ old('is_active', $landingContent->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.landing-content.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>Update
                                </button>
                                <a href="{{ route('admin.landing-content.show', $landingContent->id_content) }}" 
                                   class="btn btn-info">
                                    <i class="bi bi-eye me-1"></i>Lihat
                                </a>
                            </div>
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
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
});

// Toggle remove image checkbox
const removeImageCheckbox = document.getElementById('remove_image');
if (removeImageCheckbox) {
    removeImageCheckbox.addEventListener('change', function() {
        const imageInput = document.getElementById('image');
        if (this.checked) {
            imageInput.disabled = true;
        } else {
            imageInput.disabled = false;
        }
    });
}
</script>
@endpush