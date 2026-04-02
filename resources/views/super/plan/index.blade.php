@extends('layouts.admin')

@section('title', 'Manajemen Paket Langganan')

@section('content')
<div class="px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-cubes mr-2 text-purple-600"></i>Manajemen Paket Langganan
            </h1>
            <p class="text-gray-500 mt-1">Kelola paket langganan pondok pesantren</p>
        </div>
        <div class="flex space-x-2">
            <button type="button" onclick="exportPlans()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-file-excel mr-2"></i> Export
            </button>
            <a href="{{ route('super.plan.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Paket
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div class="text-xs font-semibold text-blue-600 uppercase">Total Paket</div>
            <div class="text-2xl font-bold text-gray-800">{{ $statistics['total_plans'] ?? 0 }}</div>
            <i class="fas fa-cubes text-gray-300 text-2xl float-right mt-2"></i>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div class="text-xs font-semibold text-green-600 uppercase">Aktif</div>
            <div class="text-2xl font-bold text-gray-800">{{ $statistics['active_plans'] ?? 0 }}</div>
            <i class="fas fa-check-circle text-gray-300 text-2xl float-right mt-2"></i>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-4">
            <div class="text-xs font-semibold text-red-600 uppercase">Nonaktif</div>
            <div class="text-2xl font-bold text-gray-800">{{ $statistics['inactive_plans'] ?? 0 }}</div>
            <i class="fas fa-ban text-gray-300 text-2xl float-right mt-2"></i>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-4">
            <div class="text-xs font-semibold text-purple-600 uppercase">Total Subscriber</div>
            <div class="text-2xl font-bold text-gray-800">{{ $statistics['total_subscribers'] ?? 0 }}</div>
            <i class="fas fa-users text-gray-300 text-2xl float-right mt-2"></i>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="border-b border-gray-200 px-6 py-4">
            <h6 class="font-bold text-purple-600">
                <i class="fas fa-filter mr-2"></i> Filter Paket
            </h6>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('super.plan.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="is_active" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Paket</label>
                    <input type="text" name="search" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Nama paket..." value="{{ request('search') }}">
                </div>
                
                <div class="flex space-x-2 items-end">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition flex-1">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    <a href="{{ route('super.plan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Plans Table -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200 px-6 py-4">
            <h6 class="font-bold text-purple-600">
                <i class="fas fa-table mr-2"></i> Daftar Paket Langganan
            </h6>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama Paket</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Slug</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Harga Bulanan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Harga Tahunan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Subscriber</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($plans as $index => $plan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $plans->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <strong>{{ $plan->name }}</strong>
                            <br>
                            <span class="text-xs text-gray-500">{{ Str::limit($plan->description, 50) }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $plan->slug }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">
                            Rp {{ number_format($plan->price_month, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">
                            Rp {{ number_format($plan->price_year, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($plan->is_active)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-ban mr-1"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-medium">
                                {{ $plan->subscriptions_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-1">
                                <a href="{{ route('super.plan.show', $plan->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 rounded" title="Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('super.plan.edit', $plan->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white p-1.5 rounded" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <button type="button" onclick="toggleStatus({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ $plan->is_active ? 'true' : 'false' }})" 
                                    class="bg-purple-500 hover:bg-purple-600 text-white p-1.5 rounded" 
                                    title="{{ $plan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fas fa-{{ $plan->is_active ? 'ban' : 'check-circle' }} text-sm"></i>
                                </button>
                                <button type="button" onclick="confirmDelete({{ $plan->id }}, '{{ addslashes($plan->name) }}')" 
                                    class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded" title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <i class="fas fa-cubes text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada data paket langganan</p>
                            <a href="{{ route('super.plan.create') }}" class="inline-block mt-3 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah Paket
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    @if($plans->count() > 0)
                        Menampilkan {{ $plans->firstItem() }} - {{ $plans->lastItem() }} dari {{ $plans->total() }} data
                    @else
                        Tidak ada data
                    @endif
                </div>
                <div>
                    {{ $plans->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function exportPlans() {
        window.location.href = "{{ route('super.plan.export') }}";
    }
    
    function toggleStatus(id, name, currentStatus) {
        const newStatus = currentStatus ? 'Nonaktifkan' : 'Aktifkan';
        if (confirm(`Apakah Anda yakin ingin ${newStatus} paket "${name}"?`)) {
            fetch(`{{ url('super/plan') }}/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Gagal mengubah status');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan');
            });
        }
    }
    
    function confirmDelete(id, name) {
        if (confirm(`Apakah Anda yakin ingin menghapus paket "${name}"?\n\nData yang dihapus tidak dapat dikembalikan!`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('super/plan') }}/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush

@endsection