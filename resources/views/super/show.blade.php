@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-circle mr-2 text-blue-500"></i>Detail User
            </h1>
            <p class="text-gray-500 mt-1">Informasi lengkap user: <strong>{{ $user->username }}</strong></p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('super.users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('super.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h6 class="font-bold text-blue-600">
                        <i class="fas fa-id-card mr-2"></i> Profile
                    </h6>
                </div>
                <div class="p-6 text-center">
                    @if($user->foto)
                        <img src="{{ Storage::url($user->foto) }}" class="rounded-full w-32 h-32 mx-auto mb-4 object-cover border-4 border-blue-500">
                    @else
                        <div class="rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white w-32 h-32 mx-auto mb-4 flex items-center justify-center">
                            <span class="text-5xl font-bold">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                        </div>
                    @endif
                    
                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $user->name }}</h3>
                    <p class="text-gray-500 mb-3">@ {{ $user->username }}</p>
                    
                    <div class="flex justify-center space-x-2 mb-4">
                        @php
                            $roleColors = [
                                'Admin' => 'blue',
                                'Super' => 'red',
                                'Pengajar' => 'green',
                                'Keuangan' => 'yellow'
                            ];
                            $color = $roleColors[$user->role] ?? 'gray';
                        @endphp
                        <span class="bg-{{ $color }}-100 text-{{ $color }}-800 px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-user-tag mr-1"></i> {{ $user->role }}
                        </span>
                        
                        @if($user->status == 'Aktif')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-check-circle mr-1"></i> Aktif
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-times-circle mr-1"></i> Nonaktif
                            </span>
                        @endif
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 text-left space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-blue-500 w-5 mt-0.5"></i>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-sm text-gray-800">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-phone text-blue-500 w-5 mt-0.5"></i>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Telepon</p>
                                <p class="text-sm text-gray-800">{{ $user->no_telp ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-building text-blue-500 w-5 mt-0.5"></i>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Pondok Pesantren</p>
                                <p class="text-sm text-gray-800">{{ $user->ponpes->nama_ponpes ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-calendar text-blue-500 w-5 mt-0.5"></i>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Bergabung</p>
                                <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-clock text-blue-500 w-5 mt-0.5"></i>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Terakhir Update</p>
                                <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($user->updated_at)->translatedFormat('d F Y H:i') }}</p>
                            </div>
                        </div>
                        @if($user->alamat)
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-blue-500 w-5 mt-0.5"></i>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Alamat</p>
                                <p class="text-sm text-gray-800">{{ $user->alamat }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Statistics Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h6 class="font-bold text-blue-600">
                        <i class="fas fa-chart-line mr-2"></i> Statistik Kehadiran
                    </h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <h3 class="text-2xl font-bold text-blue-600">{{ $statistics['total_absensi'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-600 mt-1">Total Absensi</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <h3 class="text-2xl font-bold text-green-600">{{ $statistics['hadir'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-600 mt-1">Hadir</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <h3 class="text-2xl font-bold text-yellow-600">{{ $statistics['izin'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-600 mt-1">Izin</p>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4 text-center">
                            <h3 class="text-2xl font-bold text-orange-600">{{ $statistics['sakit'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-600 mt-1">Sakit</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">Absensi Bulan Ini</span>
                            <span class="text-sm font-medium text-gray-800">{{ $statistics['absensi_bulan_ini'] ?? 0 }} dari 20 hari</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @php
                                $percentage = min(($statistics['absensi_bulan_ini'] ?? 0) / 20 * 100, 100);
                            @endphp
                            <div class="bg-green-500 h-3 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">Kehadiran (Hadir)</span>
                            <span class="text-sm font-medium text-gray-800">{{ $statistics['hadir'] ?? 0 }} dari {{ $statistics['total_absensi'] ?? 0 }} absensi</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @php
                                $kehadiranPercentage = ($statistics['total_absensi'] ?? 0) > 0 ? (($statistics['hadir'] ?? 0) / ($statistics['total_absensi'] ?? 1)) * 100 : 0;
                            @endphp
                            <div class="bg-blue-500 h-3 rounded-full transition-all" style="width: {{ $kehadiranPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Absensi -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow-md">
            <div class="border-b border-gray-200 px-6 py-4">
                <h6 class="font-bold text-blue-600">
                    <i class="fas fa-clock mr-2"></i> Riwayat Absensi Terbaru
                </h6>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jam Masuk</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jam Keluar</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Keterangan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Auto Alfa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($statistics['recent_absensi'] ?? [] as $absensi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '-' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $absensi->jam_keluar ? \Carbon\Carbon::parse($absensi->jam_keluar)->format('H:i') : '-' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'Hadir' => 'green',
                                        'Izin' => 'blue',
                                        'Sakit' => 'yellow',
                                        'Alpa' => 'red'
                                    ];
                                    $color = $statusColors[$absensi->status] ?? 'gray';
                                @endphp
                                <span class="bg-{{ $color }}-100 text-{{ $color }}-800 px-2 py-1 rounded text-xs font-medium">
                                    {{ $absensi->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $absensi->keterangan ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($absensi->is_auto_alfa)
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Ya</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">Tidak</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-calendar-times text-3xl mb-2"></i>
                                <p>Belum ada data absensi</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Activity Logs -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow-md">
            <div class="border-b border-gray-200 px-6 py-4">
                <h6 class="font-bold text-blue-600">
                    <i class="fas fa-history mr-2"></i> Log Aktivitas Terbaru
                </h6>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Deskripsi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($statistics['activity_logs'] ?? [] as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d F Y H:i:s') }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $actionColors = [
                                        'VIEW_HISTORY' => 'info',
                                        'VIEW_DETAIL' => 'primary',
                                        'UPDATE_ABSENSI' => 'warning',
                                        'CHECKOUT' => 'success',
                                        'DELETE_ABSENSI' => 'danger',
                                        'EXPORT_DATA' => 'success',
                                        'CREATE_USER' => 'success',
                                        'UPDATE_USER' => 'warning',
                                        'DELETE_USER' => 'danger',
                                        'RESET_PASSWORD' => 'info',
                                        'TOGGLE_USER_STATUS' => 'primary'
                                    ];
                                    $color = $actionColors[$log->action] ?? 'secondary';
                                @endphp
                                <span class="bg-{{ $color }}-100 text-{{ $color }}-800 px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-{{ $log->action == 'VIEW_HISTORY' ? 'eye' : ($log->action == 'UPDATE_ABSENSI' ? 'edit' : 'clock') }} mr-1"></i>
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $log->description }}</td>
                            <td class="px-4 py-3 text-sm"><code>{{ $log->ip_address ?? '-' }}</code></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-history text-3xl mb-2"></i>
                                <p>Belum ada aktivitas</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection