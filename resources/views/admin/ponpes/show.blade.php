@extends('layouts.admin')

@section('title', 'Detail Pesantren')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Detail Pesantren</h4>
                        <div>
                            <a href="{{ route('admin.ponpes.edit', $ponpes->id_ponpes) }}" 
                               class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <a href="{{ route('admin.ponpes.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    @if($ponpes->logo_ponpes)
                                    <img src="{{ Storage::url($ponpes->logo_ponpes) }}" 
                                         alt="Logo {{ $ponpes->nama_ponpes }}" 
                                         class="rounded-circle me-3" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <h3 class="mb-0">{{ $ponpes->nama_ponpes }}</h3>
                                        <p class="text-muted mb-0">ID: {{ $ponpes->id_ponpes }}</p>
                                    </div>
                                </div>
                                
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="200">Alamat</th>
                                        <td>{{ $ponpes->alamat }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tahun Berdiri</th>
                                        <td>{{ $ponpes->tahun_berdiri }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telepon</th>
                                        <td>{{ $ponpes->telp }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $ponpes->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pimpinan</th>
                                        <td>{{ $ponpes->pimpinan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-{{ $ponpes->status == 'Aktif' ? 'success' : 'danger' }}">
                                                {{ $ponpes->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Santri</th>
                                        <td>{{ number_format($ponpes->jumlah_santri) }} orang</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Staf</th>
                                        <td>{{ number_format($ponpes->jumlah_staf) }} orang</td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat</th>
                                        <td>
                                            @if($ponpes->created_at)
                                                {{ $ponpes->created_at->format('d-m-Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Diupdate</th>
                                        <td>
                                            @if($ponpes->updated_at)
                                                {{ $ponpes->updated_at->format('d-m-Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Statistics -->
                            <div class="row mb-4">
                                <div class="col-md-3 col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">{{ $statistics['total_santri'] ?? 0 }}</h5>
                                            <p class="card-text text-muted">Santri</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title text-success">{{ $statistics['active_contents'] ?? 0 }}</h5>
                                            <p class="card-text text-muted">Konten Aktif</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title text-info">{{ $statistics['total_gambar'] ?? 0 }}</h5>
                                            <p class="card-text text-muted">Gambar</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="card text-center bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title text-warning">
                                                Rp {{ number_format($statistics['total_keuangan'] ?? 0, 0, ',', '.') }}
                                            </h5>
                                            <p class="card-text text-muted">Total Keuangan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Quick Actions -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Aksi Cepat</h5>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('admin.landing-content.index', ['ponpes_id' => $ponpes->id_ponpes]) }}" 
                                       class="btn btn-outline-primary w-100 mb-2">
                                        <i class="bi bi-window me-1"></i>Kelola Konten
                                    </a>
                                    
                                    <form action="{{ route('admin.ponpes.destroy', $ponpes->id_ponpes) }}" 
                                          method="POST" class="d-inline w-100" 
                                          onsubmit="return confirm('Hapus pesantren ini? Semua data terkait akan hilang.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger w-100">
                                            <i class="bi bi-trash me-1"></i>Hapus Pesantren
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- View Landing Page -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Landing Page</h5>
                                </div>
                                <div class="card-body">
                                    <p>Lihat landing page pesantren ini:</p>
                                    <a href="{{ route('landing.show', $ponpes->id_ponpes) }}" 
                                       target="_blank" class="btn btn-success w-100">
                                        <i class="bi bi-eye me-1"></i>View Landing Page
                                    </a>
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