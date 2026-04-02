@extends('layouts.admin')

@section('title', 'Manajemen Langganan')

@section('content')
<div class="px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-users-cog mr-2 text-purple-600"></i>Manajemen Langganan
            </h1>
            <p class="text-gray-500 mt-1">Kelola langganan pondok pesantren</p>
        </div>
        <div class="flex space-x-2">
            <button type="button" onclick="exportLangganan()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-file-excel mr-2"></i> Export
            </button>
            <a href="{{ route('super.langganan.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Langganan
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div class="text-xs font-semibold text-blue-600 uppercase">Total Langganan</div>
            <div class="text-2xl font-bold text-gray-800">{{ $statistics['total_langganan'] ?? 0 }}</div>
            <i class="fas fa-users-cog text-gray-300 text-2xl float-right mt-2"></i>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div class="text-xs font-semibold text-green-600 uppercase">Aktif</div>
            <div class="text-2xl font-bold text-gray-800">{{ $statistics['active_langganan'] ?? 0 }}</div>
            <i class="fas fa-check-circle text-gray-300 text-2xl float-right mt-2"></i>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-4">
            <div class="text-xs font-semibold text-red-600 uppercase">Expired</div>
            <div class="text-2xl font-bold text-gray-800">{{ $statistics['expired_langganan'] ?? 0 }}</div>
            <i class="fas fa-clock text-gray-300 text-2xl float-right mt-2"></i>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-4">
            <div class="text-xs font-semibold text-yellow-600 uppercase">Trial</div>
            <div class="text-2xl font-bold text-gray-800">{{ $statistics['pending_langganan'] ?? 0 }}</div>
            <i class="fas fa-hourglass-half text-gray-300 text-2xl float-right mt-2"></i>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-indigo-500 p-4">
            <div class="text-xs font-semibold text-indigo-600 uppercase">Total Revenue</div>
            <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($statistics['total_revenue'] ?? 0, 0, ',', '.') }}</div>
            <i class="fas fa-money-bill-wave text-gray-300 text-2xl float-right mt-2"></i>
        </div>
        
        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-4">
            <div class="text-xs font-semibold text-purple-600 uppercase">Revenue Bulan Ini</div>
            <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($statistics['revenue_this_month'] ?? 0, 0, ',', '.') }}</div>
            <i class="fas fa-chart-line text-gray-300 text-2xl float-right mt-2"></i>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="border-b border-gray-200 px-6 py-4">
            <h6 class="font-bold text-purple-600">
                <i class="fas fa-filter mr-2"></i> Filter Langganan
            </h6>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('super.langganan.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pondok Pesantren</label>
                    <input type="text" name="search" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Nama Pondok Pesantren..." value="{{ request('search') }}">
                </div>
                
                <div class="flex space-x-2 items-end">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition flex-1">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    <a href="{{ route('super.langganan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Langganan Table -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h6 class="font-bold text-purple-600">
                <i class="fas fa-table mr-2"></i> Daftar Langganan
            </h6>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pondok Pesantren</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Paket</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Siklus</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Sisa Hari</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Dibuat</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($langganan as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $langganan->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <strong>{{ $item->ponpes->nama_ponpes ?? 'N/A' }}</strong>
                            <br>
                            <span class="text-xs text-gray-500">{{ $item->ponpes->pimpinan ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-medium">
                                {{ $item->plan->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">
                            Rp {{ number_format($item->amount ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs {{ $item->billing_cycle == 'yearly' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }} px-2 py-1 rounded">
                                {{ $item->billing_cycle == 'yearly' ? 'Tahunan' : 'Bulanan' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div>{{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">s/d</div>
                            <div>{{ \Carbon\Carbon::parse($item->current_period_end)->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $remainingDays = \Carbon\Carbon::now()->diffInDays($item->current_period_end, false);
                            @endphp
                            @if($item->status == 'active' && $remainingDays > 0)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                    {{ ceil($remainingDays) }} hari
                                </span>
                            @elseif($item->status == 'active' && $remainingDays <= 0)
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">
                                    Expired
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'active' => 'green',
                                    'expired' => 'red',
                                    'trial' => 'yellow',
                                    'canceled' => 'gray',
                                    'past_due' => 'orange'
                                ];
                                $color = $statusColors[$item->status] ?? 'gray';
                                $statusText = [
                                    'active' => 'Aktif',
                                    'expired' => 'Expired',
                                    'trial' => 'Trial',
                                    'canceled' => 'Dibatalkan',
                                    'past_due' => 'Menunggak'
                                ];
                                $statusIcons = [
                                    'active' => 'fa-check-circle',
                                    'expired' => 'fa-times-circle',
                                    'trial' => 'fa-hourglass-half',
                                    'canceled' => 'fa-ban',
                                    'past_due' => 'fa-exclamation-triangle'
                                ];
                                $icon = $statusIcons[$item->status] ?? 'fa-circle';
                            @endphp
                            <span class="bg-{{ $color }}-100 text-{{ $color }}-800 px-2 py-1 rounded text-xs font-medium">
                                <i class="fas {{ $icon }} mr-1"></i>
                                {{ $statusText[$item->status] ?? $item->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}<\/td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-1">
                                <a href="{{ route('super.langganan.show', $item->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 rounded" title="Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('super.langganan.edit', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white p-1.5 rounded" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                @if($item->status == 'active' || $item->status == 'trial')
                                <button type="button" onclick="renewLangganan({{ $item->id }}, '{{ addslashes($item->ponpes->nama_ponpes) }}')" class="bg-purple-500 hover:bg-purple-600 text-white p-1.5 rounded" title="Perpanjang">
                                    <i class="fas fa-sync-alt text-sm"></i>
                                </button>
                                @endif
                                <button type="button" onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->ponpes->nama_ponpes) }}')" class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded" title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        <\/td>
                    <\/tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center">
                            <i class="fas fa-users-cog text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada data langganan</p>
                            <a href="{{ route('super.langganan.create') }}" class="inline-block mt-3 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-plus mr-1"></i> Tambah Langganan
                            </a>
                        <\/td>
                    <\/tr>
                    @endforelse
                </tbody>
            <\/table>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    @if($langganan->count() > 0)
                        Menampilkan {{ $langganan->firstItem() }} - {{ $langganan->lastItem() }} dari {{ $langganan->total() }} data
                    @else
                        Tidak ada data
                    @endif
                </div>
                <div>
                    {{ $langganan->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Renew Langganan -->
<div id="renewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg w-full max-w-md">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h5 class="text-xl font-bold text-gray-800">Perpanjang Langganan</h5>
            <button type="button" onclick="closeRenewModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="renewForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pondok Pesantren</label>
                    <input type="text" id="renewPonpesName" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (Bulan)</label>
                    <select name="duration" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                        <option value="1">1 Bulan</option>
                        <option value="3">3 Bulan</option>
                        <option value="6">6 Bulan</option>
                        <option value="12">12 Bulan (1 Tahun)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pembayaran</label>
                    <input type="number" name="amount" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Pilih Metode</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="cash">Tunai</option>
                        <option value="virtual_account">Virtual Account</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran</label>
                    <input type="file" name="payment_proof" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">Format: jpeg, png, jpg | Max: 2MB</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" rows="3"></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 p-6 border-t border-gray-200">
                <button type="button" onclick="closeRenewModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i> Perpanjang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let renewLanggananId = null;

    function exportLangganan() {
        const url = new URL(window.location.href);
        url.pathname = "{{ route('super.langganan.export') }}";
        window.location.href = url.toString();
    }

    function confirmDelete(id, ponpesName) {
        if (confirm(`Apakah Anda yakin ingin menghapus langganan untuk "${ponpesName}"?\n\nData yang dihapus tidak dapat dikembalikan!`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('super/langganan') }}/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function renewLangganan(id, ponpesName) {
        renewLanggananId = id;
        document.getElementById('renewPonpesName').value = ponpesName;
        document.getElementById('renewForm').action = `{{ url('super/langganan') }}/${id}/renew`;
        document.getElementById('renewModal').classList.remove('hidden');
    }

    function closeRenewModal() {
        document.getElementById('renewModal').classList.add('hidden');
        document.getElementById('renewForm').reset();
    }

    document.getElementById('renewForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        this.submit();
    });
</script>
@endpush

@endsection