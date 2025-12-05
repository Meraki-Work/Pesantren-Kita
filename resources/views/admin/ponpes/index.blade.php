@extends('layouts.admin')

@section('title', 'Kelola Pesantren')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Daftar Pesantren</h4>
                        <a href="{{ route('admin.ponpes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Pesantren
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($ponpesList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Logo</th>
                                    <th>Nama Pesantren</th>
                                    <th>Alamat</th>
                                    <th>Tahun Berdiri</th>
                                    <th>Status</th>
                                    <th>Jumlah Santri</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ponpesList as $ponpes)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($ponpes->logo_ponpes)
                                        <img src="{{ Storage::url($ponpes->logo_ponpes) }}" 
                                             alt="Logo" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; border-radius: 5px;">
                                            <i class="bi bi-house text-muted"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $ponpes->nama_ponpes }}</strong><br>
                                        <small class="text-muted">{{ $ponpes->pimpinan }}</small>
                                    </td>
                                    <td>{{ Str::limit($ponpes->alamat, 50) }}</td>
                                    <td>{{ $ponpes->tahun_berdiri }}</td>
                                    <td>
                                        <span class="badge bg-{{ $ponpes->status == 'Aktif' ? 'success' : 'danger' }}">
                                            {{ $ponpes->status }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($ponpes->jumlah_santri) }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.ponpes.show', $ponpes->id_ponpes) }}" 
                                               class="btn btn-info" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.ponpes.edit', $ponpes->id_ponpes) }}" 
                                               class="btn btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.ponpes.destroy', $ponpes->id_ponpes) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" 
                                                        title="Hapus" 
                                                        onclick="return confirm('Hapus pesantren ini?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $ponpesList->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-house" style="font-size: 48px;"></i>
                            <p class="mt-3">Belum ada data pesantren</p>
                            <a href="{{ route('admin.ponpes.create') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-plus-circle me-1"></i>Tambah Pesantren Pertama
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection