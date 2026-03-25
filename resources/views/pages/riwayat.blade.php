@extends('index')

@section('title', 'Sanksi')

@section('content')
<!-- System Logs Section -->
<div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-history mr-2 text-emerald-600"></i>Log Aktivitas Terbaru
            </h3>
            <button onclick="refreshLogs()" class="text-sm text-emerald-600 hover:text-emerald-700">
                <i class="fas fa-sync-alt mr-1"></i> Refresh
            </button>
        </div>
    </div>
    
    <div class="divide-y divide-gray-200">
        @forelse($systemLogs as $log)
        <div class="px-6 py-4 hover:bg-gray-50 transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        @php
                            $logIcons = [
                                'VIEW_HISTORY' => 'fa-eye text-blue-500',
                                'VIEW_DETAIL' => 'fa-info-circle text-indigo-500',
                                'UPDATE_ABSENSI' => 'fa-edit text-green-500',
                                'CHECKOUT' => 'fa-sign-out-alt text-red-500',
                                'DELETE_ABSENSI' => 'fa-trash text-red-600',
                                'EXPORT_DATA' => 'fa-file-excel text-emerald-500'
                            ];
                            $iconClass = $logIcons[$log->action] ?? 'fa-clock text-gray-500';
                        @endphp
                        <i class="fas {{ $iconClass }} mr-2"></i>
                        <span class="text-sm font-medium text-gray-900">{{ $log->description }}</span>
                    </div>
                    <div class="text-xs text-gray-500 space-x-3">
                        <span>
                            <i class="fas fa-user mr-1"></i> {{ $log->username }}
                        </span>
                        <span>
                            <i class="fas fa-clock mr-1"></i> {{ Carbon\Carbon::parse($log->created_at)->translatedFormat('d F Y H:i:s') }}
                        </span>
                        <span>
                            <i class="fas fa-laptop mr-1"></i> {{ $log->ip_address }}
                        </span>
                    </div>
                    @if($log->data)
                    <div class="mt-2 text-xs text-gray-400">
                        <details>
                            <summary class="cursor-pointer">Detail Data</summary>
                            <pre class="mt-1 p-2 bg-gray-100 rounded text-xs overflow-x-auto">{{ json_encode(json_decode($log->data), JSON_PRETTY_PRINT) }}</pre>
                        </details>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-8 text-center text-gray-500">
            <i class="fas fa-inbox text-3xl mb-2"></i>
            <p>Belum ada aktivitas yang tercatat</p>
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
function refreshLogs() {
    fetch('/absensi/logs')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to show updated logs
                location.reload();
            }
        });
}
</script>
@endpush