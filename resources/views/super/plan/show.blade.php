@extends('layouts.admin')

@section('title', 'Detail Paket Langganan')

@section('content')
<div class="px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-cube mr-2 text-purple-600"></i>Detail Paket Langganan
            </h1>
            <p class="text-gray-500 mt-1">Detail paket: <strong>{{ $plan->name }}</strong></p>
        </div>
        <div>
            <a href="{{ route('super.plan.edit', $plan->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition inline-flex items-center mr-2">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('super.plan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Utama -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-info-circle mr-2 text-purple-600"></i>Informasi Paket
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Paket</label>
                            <p class="text-gray-900 font-medium">{{ $plan->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Slug</label>
                            <p class="text-gray-900">{{ $plan->slug }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            @if($plan->is_active)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">
                                    <i class="fas fa-ban mr-1"></i> Nonaktif
                                </span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dibuat Pada</label>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($plan->created_at)->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $plan->description ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Harga & Statistik -->
        <div>
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-tag mr-2 text-purple-600"></i>Harga
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="bg-purple-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500">Bulanan</p>
                            <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($plan->price_month, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 mt-3">
                            <p class="text-sm text-gray-500">Tahunan</p>
                            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($plan->price_year, 0, ',', '.') }}</p>
                            <p class="text-xs text-green-600 mt-1">(Hemat {{ number_format(($plan->price_month * 12) - $plan->price_year, 0, ',', '.') }})</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-chart-line mr-2 text-purple-600"></i>Statistik
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Subscriber</span>
                        <span class="font-bold text-gray-800">{{ $statistics['total_subscribers'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Subscriber Aktif</span>
                        <span class="font-bold text-green-600">{{ $statistics['active_subscribers'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Revenue Bulanan</span>
                        <span class="font-bold text-purple-600">Rp {{ number_format($statistics['monthly_revenue'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Revenue Tahunan</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($statistics['yearly_revenue'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Limit Fitur -->
    <div class="bg-white rounded-lg shadow-md mt-6">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-chart-bar mr-2 text-purple-600"></i>Limit Fitur
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @php
                    $limits = $plan->limits_json ?? [];
                    $limitItems = [
                        'max_users' => ['icon' => 'fa-users', 'label' => 'Max Users'],
                        'max_santri' => ['icon' => 'fa-child', 'label' => 'Max Santri'],
                        'max_inventaris' => ['icon' => 'fa-boxes', 'label' => 'Max Inventaris'],
                        'max_laundry' => ['icon' => 'fa-tshirt', 'label' => 'Max Laundry'],
                        'max_notulen' => ['icon' => 'fa-file-alt', 'label' => 'Max Notulen'],
                        'storage_mb' => ['icon' => 'fa-database', 'label' => 'Storage']
                    ];
                @endphp
                @foreach($limitItems as $key => $item)
                <div class="bg-gray-50 rounded-lg p-3 text-center">
                    <i class="fas {{ $item['icon'] }} text-purple-500 text-xl mb-2 block"></i>
                    <p class="text-xs text-gray-500">{{ $item['label'] }}</p>
                    <p class="text-sm font-bold text-gray-800">
                        @if(isset($limits[$key]))
                            @if($limits[$key] == -1)
                                Unlimited
                            @else
                                {{ number_format($limits[$key]) }}
                                @if($key == 'storage_mb') MB @endif
                            @endif
                        @else
                            -
                        @endif
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Fitur Khusus -->
    @if($plan->features && $plan->features->count() > 0)
    <div class="bg-white rounded-lg shadow-md mt-6">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-star mr-2 text-purple-600"></i>Fitur Khusus
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($plan->features as $feature)
                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg">
                    <i class="fas fa-{{ $feature->enabled ? 'check-circle text-green-500' : 'times-circle text-red-500' }}"></i>
                    <span class="text-gray-700 {{ !$feature->enabled ? 'line-through text-gray-400' : '' }}">
                        {{ str_replace('_', ' ', ucwords($feature->feature_key)) }}
                    </span>
                    @if(!$feature->enabled)
                        <span class="text-xs text-gray-400">(Tidak Aktif)</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Daftar Subscriber -->
    @if(isset($plan->subscriptions) && $plan->subscriptions->count() > 0)
    <div class="bg-white rounded-lg shadow-md mt-6">
        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-users mr-2 text-purple-600"></i>Subscriber Aktif (10 terbaru)
            </h3>
            <a href="{{ route('super.langganan.index', ['plan_id' => $plan->id]) }}" class="text-purple-600 hover:text-purple-800 text-sm">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Pondok Pesantren</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Siklus</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Mulai</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Berakhir</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($plan->subscriptions as $sub)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <strong>{{ $sub->ponpes->nama_ponpes ?? 'N/A' }}</strong>
                        </td>
                        <td class="px-4 py-2">
                            <span class="text-xs {{ $sub->billing_cycle == 'yearly' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }} px-2 py-1 rounded">
                                {{ $sub->billing_cycle == 'yearly' ? 'Tahunan' : 'Bulanan' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            @php
                                $statusColors = [
                                    'active' => 'green',
                                    'trial' => 'yellow',
                                    'expired' => 'red',
                                    'canceled' => 'gray'
                                ];
                                $color = $statusColors[$sub->status] ?? 'gray';
                            @endphp
                            <span class="bg-{{ $color }}-100 text-{{ $color }}-800 px-2 py-1 rounded text-xs font-medium">
                                {{ ucfirst($sub->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($sub->start_date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-sm">{{ \Carbon\Carbon::parse($sub->current_period_end)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('super.langganan.show', $sub->id) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection