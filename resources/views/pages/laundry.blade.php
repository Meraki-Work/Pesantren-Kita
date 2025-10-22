@extends('index')

@section('content')
<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />
    
    <main class="flex-1 p-6 overflow-hidden">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Keuangan</h1>
            <p class="text-gray-600">Kelola pemasukan dan pengeluaran pesantren</p>
        </div>

        <!-- Statistik -->
        @php
            $stats = app('App\Http\Controllers\LaundryController')->getStatistics();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Total Pemasukan -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pemasukan</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($stats['total_pemasukan'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Pengeluaran -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($stats['total_pengeluaran'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Saldo -->
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Saldo</p>
                        <p class="text-xl font-bold {{ $stats['saldo'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($stats['saldo'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Form Tambah Transaksi -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Transaksi</h3>

                <!-- Alert Messages -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('laundry.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Santri -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Santri</label>
                        <select name="id_santri" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                            <option value="">-- Pilih Santri --</option>
                            @foreach($santri as $s)
                            <option value="{{ $s->id_santri }}">{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="id_kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sumber Dana -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana <span class="text-red-500">*</span></label>
                        <input type="text" name="sumber_dana" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" placeholder="Contoh: SPP Bulanan, Donasi, dll" required>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" placeholder="Deskripsi transaksi..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Jumlah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" step="0.01" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" placeholder="0.00" required>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Masuk">Pemasukan</option>
                                <option value="Keluar">Pengeluaran</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" required>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="reset" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150 font-medium">
                            Reset
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Transaksi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Daftar Transaksi Laundry -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Transaksi Laundry</h3>
            <p class="text-sm text-gray-600 mt-1">Hanya menampilkan transaksi kategori laundry</p>
        </div>
        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
            {{ $keuangan->count() }} Data
        </span>
    </div>

    @if($keuangan->count() > 0)
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Santri</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Kategori</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Sumber Dana</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Jumlah</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($keuangan as $item)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->nama_santri ?? 'Umum' }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm text-gray-600">
                            <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                {{ $item->nama_kategori ?? 'Laundry' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900">{{ $item->sumber_dana }}</div>
                        @if($item->keterangan)
                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($item->keterangan, 40) }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm font-medium {{ $item->status == 'Masuk' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $item->status == 'Masuk' ? '+' : '-' }} Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </span>
                            <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $item->status == 'Masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->status }}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <!-- Tombol Edit -->
                            <a href="{{ route('laundry.edit', $item->id_keuangan) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition duration-150 text-sm font-medium"
                               title="Edit transaksi laundry">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            
                            <!-- Tombol Hapus -->
                            <form action="{{ route('laundry.destroy', $item->id_keuangan) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="confirmDelete('{{ $item->id_keuangan }}', '{{ $item->sumber_dana }}')"
                                        class="inline-flex items-center px-3 py-1 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition duration-150 text-sm font-medium"
                                        title="Hapus transaksi laundry">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <h4 class="text-lg font-medium text-gray-600 mb-2">Belum ada data transaksi laundry</h4>
        <p class="text-gray-500 text-sm">Tambahkan data transaksi laundry pertama menggunakan form di samping</p>
    </div>
    @endif
</div>
        </div>

        <script>
            // Set tanggal hari ini sebagai default
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date().toISOString().split('T')[0];
                document.querySelector('input[name="tanggal"]').value = today;
            });

            // Fungsi konfirmasi hapus
            function confirmDelete(id, sumberDana) {
                if (confirm(`Apakah Anda yakin ingin menghapus transaksi:\n"${sumberDana}"?`)) {
                    const form = document.querySelector(`form[action*="${id}"]`);
                    if (form) {
                        form.submit();
                    }
                }
            }

            // Format input jumlah saat ketik
            document.querySelector('input[name="jumlah"]').addEventListener('input', function(e) {
                // Remove non-numeric characters except decimal point
                this.value = this.value.replace(/[^\d.]/g, '');
                
                // Ensure only one decimal point
                const parts = this.value.split('.');
                if (parts.length > 2) {
                    this.value = parts[0] + '.' + parts.slice(1).join('');
                }
            });
        </script>
    </main>
</div>
@endsection