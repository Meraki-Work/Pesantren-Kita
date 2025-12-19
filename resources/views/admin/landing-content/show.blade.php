@extends('index')

@section('title', 'Detail Konten Landing Page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Detail Konten Landing Page</h4>
                        <div>
                            <a href="{{ route('admin.landing-content.edit', $landingContent->id_content) }}" 
                               class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <a href="{{ route('admin.landing-content.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h5>Informasi Konten</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="200">Judul</th>
                                        <td>{{ $landingContent->title ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tipe Konten</th>
                                        <td>
                                            <span class="badge 
                                                @if($landingContent->content_type == 'carousel') bg-primary
                                                @elseif($landingContent->content_type == 'about_founder') bg-success
                                                @elseif($landingContent->content_type == 'about_leader') bg-info
                                                @elseif($landingContent->content_type == 'footer') bg-secondary
                                                @else bg-warning text-dark
                                                @endif">
                                                {{ $landingContent->content_type }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Pesantren</th>
                                        <td>{{ $landingContent->ponpes->nama_ponpes ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Posisi/Jabatan</th>
                                        <td>{{ $landingContent->position ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subjudul</th>
                                        <td>{{ $landingContent->subtitle ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>URL/Link</th>
                                        <td>
                                            @if($landingContent->url)
                                            <a href="{{ $landingContent->url }}" target="_blank">
                                                {{ $landingContent->url }}
                                            </a>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Urutan Tampilan</th>
                                        <td>{{ $landingContent->display_order }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($landingContent->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                            @else
                                            <span class="badge bg-danger">Nonaktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat</th>
                                        <td>{{ $landingContent->created_at->translatedFormat('d F Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Diperbarui</th>
                                        <td>{{ $landingContent->updated_at->translatedFormat('d F Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            @if($landingContent->description)
                            <div class="mb-4">
                                <h5>Deskripsi Lengkap</h5>
                                <div class="card">
                                    <div class="card-body">
                                        {!! nl2br(e($landingContent->description)) !!}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Image Preview -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Preview Gambar</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($landingContent->image)
                                    <img src="{{ Storage::url($landingContent->image) }}" 
                                         alt="{{ $landingContent->title }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 300px;">
                                    <div class="mt-3">
                                        <a href="{{ Storage::url($landingContent->image) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-zoom-in me-1"></i>Lihat Full Size
                                        </a>
                                    </div>
                                    @else
                                    <div class="py-5 text-center text-muted">
                                        <i class="bi bi-image" style="font-size: 48px;"></i>
                                        <p class="mt-2">Tidak ada gambar</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Preview Section -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Preview di Frontend</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Konten ini akan muncul di:</p>
                                    <ul>
                                        @if($landingContent->content_type == 'carousel')
                                        <li>Bagian carousel utama</li>
                                        @elseif($landingContent->content_type == 'about_founder')
                                        <li>Bagian tentang pendiri</li>
                                        @elseif($landingContent->content_type == 'about_leader')
                                        <li>Bagian tentang pimpinan</li>
                                        @elseif($landingContent->content_type == 'footer')
                                        <li>Bagian footer</li>
                                        @elseif($landingContent->content_type == 'section_title')
                                        <li>Judul section</li>
                                        @endif
                                    </ul>
                                    
                                    @if($landingContent->ponpes)
                                    <a href="{{ route('landing.show', $landingContent->ponpes_id) }}" 
                                       target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-eye me-1"></i>Lihat di Landing Page
                                    </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Aksi Cepat</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.landing-content.destroy', $landingContent->id_content) }}" 
                                          method="POST" class="d-inline" onsubmit="return confirm('Hapus konten ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100 mb-2">
                                            <i class="bi bi-trash me-1"></i>Hapus Konten
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.landing-content.toggle-status', $landingContent->id_content) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_active" 
                                               value="{{ $landingContent->is_active ? 0 : 1 }}">
                                        <button type="submit" class="btn btn-{{ $landingContent->is_active ? 'warning' : 'success' }} w-100">
                                            <i class="bi bi-power me-1"></i>
                                            {{ $landingContent->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
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