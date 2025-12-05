@extends('layouts.admin')

@section('title', 'Edit Pesantren')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Edit Pesantren</h4>
                        <a href="{{ route('admin.ponpes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.ponpes.update', $ponpes->id_ponpes) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_ponpes" class="form-label">ID Pesantren</label>
                                    <input type="text" name="id_ponpes" id="id_ponpes" 
                                           class="form-control @error('id_ponpes') is-invalid @enderror" 
                                           value="{{ old('id_ponpes', $ponpes->id_ponpes) }}" 
                                           readonly>
                                    @error('id_ponpes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">ID tidak dapat diubah</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_ponpes" class="form-label">Nama Pesantren *</label>
                                    <input type="text" name="nama_ponpes" id="nama_ponpes" 
                                           class="form-control @error('nama_ponpes') is-invalid @enderror" 
                                           value="{{ old('nama_ponpes', $ponpes->nama_ponpes) }}" 
                                           required>
                                    @error('nama_ponpes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat *</label>
                            <textarea name="alamat" id="alamat" 
                                      class="form-control @error('alamat') is-invalid @enderror" 
                                      rows="3" required>{{ old('alamat', $ponpes->alamat) }}</textarea>
                            @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tahun_berdiri" class="form-label">Tahun Berdiri *</label>
                                    <input type="number" name="tahun_berdiri" id="tahun_berdiri" 
                                           class="form-control @error('tahun_berdiri') is-invalid @enderror" 
                                           value="{{ old('tahun_berdiri', $ponpes->tahun_berdiri) }}" 
                                           min="1900" max="2100" required>
                                    @error('tahun_berdiri')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="telp" class="form-label">Telepon *</label>
                                    <input type="text" name="telp" id="telp" 
                                           class="form-control @error('telp') is-invalid @enderror" 
                                           value="{{ old('telp', $ponpes->telp) }}" required>
                                    @error('telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" name="email" id="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $ponpes->email) }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="logo_ponpes" class="form-label">Logo Pesantren</label>
                                    
                                    @if($ponpes->logo_ponpes)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($ponpes->logo_ponpes) }}" 
                                             alt="Logo" 
                                             class="img-thumbnail" 
                                             style="max-width: 150px;">
                                        <div class="mt-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="remove_logo" id="remove_logo" value="1">
                                                <label class="form-check-label text-danger" for="remove_logo">
                                                    Hapus logo ini
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <input type="file" name="logo_ponpes" id="logo_ponpes" 
                                           class="form-control @error('logo_ponpes') is-invalid @enderror" 
                                           accept="image/*">
                                    @error('logo_ponpes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Format: JPG, PNG, GIF. Maks: 2MB</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="jumlah_santri" class="form-label">Jumlah Santri</label>
                                            <input type="number" name="jumlah_santri" id="jumlah_santri" 
                                                   class="form-control @error('jumlah_santri') is-invalid @enderror" 
                                                   value="{{ old('jumlah_santri', $ponpes->jumlah_santri) }}" 
                                                   min="0">
                                            @error('jumlah_santri')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="jumlah_staf" class="form-label">Jumlah Staf</label>
                                            <input type="number" name="jumlah_staf" id="jumlah_staf" 
                                                   class="form-control @error('jumlah_staf') is-invalid @enderror" 
                                                   value="{{ old('jumlah_staf', $ponpes->jumlah_staf) }}" 
                                                   min="0">
                                            @error('jumlah_staf')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pimpinan" class="form-label">Pimpinan *</label>
                                    <input type="text" name="pimpinan" id="pimpinan" 
                                           class="form-control @error('pimpinan') is-invalid @enderror" 
                                           value="{{ old('pimpinan', $ponpes->pimpinan) }}" required>
                                    @error('pimpinan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select name="status" id="status" 
                                            class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="Aktif" {{ old('status', $ponpes->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Nonaktif" {{ old('status', $ponpes->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('admin.ponpes.show', $ponpes->id_ponpes) }}" 
                                   class="btn btn-info">
                                    <i class="bi bi-eye me-1"></i>Lihat
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle remove logo checkbox
const removeLogoCheckbox = document.getElementById('remove_logo');
if (removeLogoCheckbox) {
    removeLogoCheckbox.addEventListener('change', function() {
        const logoInput = document.getElementById('logo_ponpes');
        if (this.checked) {
            logoInput.disabled = true;
        } else {
            logoInput.disabled = false;
        }
    });
}
</script>
@endpush
@endsection