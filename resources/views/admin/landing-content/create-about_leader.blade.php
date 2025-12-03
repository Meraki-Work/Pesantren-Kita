@extends('layouts.admin')

@section('title', 'Tambah Leader/Pengurus')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0 fw-bold text-info">
                                <i class="bi bi-person-square me-2"></i>Tambah Leader/Pengurus
                            </h4>
                            <p class="text-muted mb-0 small">Profil pengurus pesantren</p>
                        </div>
                        <a href="{{ route('admin.landing-content.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <input type="hidden" name="content_type" value="about_leader">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Image Upload Card -->
                                <div class="card border h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Foto Pengurus <span class="text-danger">*</span></h6>
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
                                                <i class="bi bi-person text-muted display-4"></i>
                                                <p class="mt-2 text-muted small">Foto pengurus akan muncul di sini</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Position Badge -->
                                        <div class="mt-3">
                                            <div class="alert alert-info bg-info bg-opacity-10 border-info">
                                                <small>
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    Pastikan foto formal dan profesional
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="card border h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Informasi Pengurus</h6>
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
                                                    <label for="title" class="form-label">Nama Pengurus <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" id="title" 
                                                           class="form-control @error('title') is-invalid @enderror" 
                                                           value="{{ old('title') }}" 
                                                           placeholder="Contoh: Drs. Muhammad Ali" required>
                                                    @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="position" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                                    <select name="position" id="position" 
                                                            class="form-select @error('position') is-invalid @enderror" required>
                                                        <option value="">Pilih Jabatan</option>
                                                        <option value="Ketua Yayasan" {{ old('position') == 'Ketua Yayasan' ? 'selected' : '' }}>Ketua Yayasan</option>
                                                        <option value="Sekretaris Yayasan" {{ old('position') == 'Sekretaris Yayasan' ? 'selected' : '' }}>Sekretaris Yayasan</option>
                                                        <option value="Bendahara Yayasan" {{ old('position') == 'Bendahara Yayasan' ? 'selected' : '' }}>Bendahara Yayasan</option>
                                                        <option value="Ketua Pesantren" {{ old('position') == 'Ketua Pesantren' ? 'selected' : '' }}>Ketua Pesantren</option>
                                                        <option value="Wakil Ketua" {{ old('position') == 'Wakil Ketua' ? 'selected' : '' }}>Wakil Ketua</option>
                                                        <option value="Kepala Madrasah" {{ old('position') == 'Kepala Madrasah' ? 'selected' : '' }}>Kepala Madrasah</option>
                                                        <option value="Wakil Kepala Madrasah" {{ old('position') == 'Wakil Kepala Madrasah' ? 'selected' : '' }}>Wakil Kepala Madrasah</option>
                                                        <option value="Ketua Bagian" {{ old('position') == 'Ketua Bagian' ? 'selected' : '' }}>Ketua Bagian</option>
                                                        <option value="Lainnya" {{ old('position') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                                    </select>
                                                    @error('position')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Profil Singkat <span class="text-danger">*</span></label>
                                            <textarea name="description" id="description" 
                                                      class="form-control @error('description') is-invalid @enderror" 
                                                      rows="5" 
                                                      placeholder="Tuliskan profil singkat pengurus..." required>{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Maksimal 500 karakter</small>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="url" class="form-label">Kontak/Link (Opsional)</label>
                                                    <input type="url" name="url" id="url" 
                                                           class="form-control @error('url') is-invalid @enderror" 
                                                           value="{{ old('url') }}" 
                                                           placeholder="https://wa.me/6281234567890">
                                                    @error('url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Link WhatsApp, email, atau sosial media</small>
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
                                                    <small class="text-muted">Untuk mengatur urutan tampil di daftar pengurus</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
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
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="featured" id="featured" value="1"
                                                               {{ old('featured') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="featured">
                                                            <span class="fw-semibold">Tandai sebagai Utama</span>
                                                        </label>
                                                        <small class="text-muted d-block">Akan ditampilkan lebih menonjol</small>
                                                    </div>
                                                </div>
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
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-save me-1"></i>Simpan Pengurus
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