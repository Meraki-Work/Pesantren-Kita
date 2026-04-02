@extends('layouts.admin')

@section('title', 'Edit Paket Langganan')

@section('content')
<div class="px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-edit mr-2 text-yellow-600"></i>Edit Paket Langganan
            </h1>
            <p class="text-gray-500 mt-1">Edit paket: <strong>{{ $plan->name }}</strong></p>
        </div>
        <a href="{{ route('super.plan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        <form action="{{ route('super.plan.update', $plan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-6">
                <!-- Nama Paket -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Paket <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 @error('name') border-red-500 @enderror" value="{{ old('name', $plan->name) }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 @error('description') border-red-500 @enderror" rows="4">{{ old('description', $plan->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Bulanan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" name="price_month" class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 @error('price_month') border-red-500 @enderror" value="{{ old('price_month', $plan->price_month) }}" required>
                        </div>
                        @error('price_month')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Tahunan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" name="price_year" class="w-full pl-10 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 @error('price_year') border-red-500 @enderror" value="{{ old('price_year', $plan->price_year) }}" required>
                        </div>
                        @error('price_year')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" class="mr-2" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Aktif</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Paket aktif dapat dipilih oleh pondok pesantren</p>
                </div>

                <!-- Limit Fitur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Limit Fitur</label>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @php
                                $limits = $plan->limits_json ?? [];
                            @endphp
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Max Users</label>
                                <input type="number" name="limits[max_users]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" value="{{ old('limits.max_users', $limits['max_users'] ?? -1) }}" placeholder="-1 untuk unlimited">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Max Santri</label>
                                <input type="number" name="limits[max_santri]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" value="{{ old('limits.max_santri', $limits['max_santri'] ?? -1) }}" placeholder="-1 untuk unlimited">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Max Inventaris</label>
                                <input type="number" name="limits[max_inventaris]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" value="{{ old('limits.max_inventaris', $limits['max_inventaris'] ?? -1) }}" placeholder="-1 untuk unlimited">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Max Laundry</label>
                                <input type="number" name="limits[max_laundry]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" value="{{ old('limits.max_laundry', $limits['max_laundry'] ?? -1) }}" placeholder="-1 untuk unlimited">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Max Notulen</label>
                                <input type="number" name="limits[max_notulen]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" value="{{ old('limits.max_notulen', $limits['max_notulen'] ?? -1) }}" placeholder="-1 untuk unlimited">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Storage (MB)</label>
                                <input type="number" name="limits[storage_mb]" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" value="{{ old('limits.storage_mb', $limits['storage_mb'] ?? 100) }}" placeholder="100">
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Isi dengan -1 untuk unlimited, kosongkan jika tidak ada limit</p>
                </div>

                <!-- Fitur Khusus -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-medium text-gray-700">Fitur Khusus</label>
                        <button type="button" onclick="addFeature()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Fitur
                        </button>
                    </div>
                    
                    <div id="featuresContainer" class="space-y-2">
                        @if($plan->features && $plan->features->count() > 0)
                            @foreach($plan->features as $index => $feature)
                            <div class="feature-item flex items-center space-x-2 mb-2">
                                <input type="text" name="features[{{ $index }}][key]" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Nama fitur (contoh: manajemen_keuangan)" value="{{ $feature->feature_key }}" required>
                                <select name="features[{{ $index }}][value]" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="true" {{ $feature->enabled ? 'selected' : '' }}>Aktif</option>
                                    <option value="false" {{ !$feature->enabled ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                <button type="button" onclick="removeFeature(this)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center text-gray-500 py-4" id="emptyFeatures">
                                <i class="fas fa-info-circle mr-1"></i> Belum ada fitur. Klik tombol tambah fitur.
                            </div>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Fitur yang ditambahkan akan ditampilkan di detail paket</p>
                </div>
            </div>

            <div class="flex justify-end space-x-3 p-6 border-t border-gray-200">
                <a href="{{ route('super.plan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Batal
                </a>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i> Update Paket
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let featureIndex = {{ isset($plan->features) ? $plan->features->count() : 0 }};
    
    function addFeature() {
        const container = document.getElementById('featuresContainer');
        const emptyDiv = document.getElementById('emptyFeatures');
        if (emptyDiv) emptyDiv.style.display = 'none';
        
        const newFeature = document.createElement('div');
        newFeature.className = 'feature-item flex items-center space-x-2 mb-2';
        newFeature.innerHTML = `
            <input type="text" name="features[${featureIndex}][key]" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Nama fitur (contoh: manajemen_keuangan)" required>
            <select name="features[${featureIndex}][value]" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="true">Aktif</option>
                <option value="false">Tidak Aktif</option>
            </select>
            <button type="button" onclick="removeFeature(this)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(newFeature);
        featureIndex++;
    }
    
    function removeFeature(button) {
        button.closest('.feature-item').remove();
        const container = document.getElementById('featuresContainer');
        if (container.children.length === 0) {
            const emptyDiv = document.createElement('div');
            emptyDiv.id = 'emptyFeatures';
            emptyDiv.className = 'text-center text-gray-500 py-4';
            emptyDiv.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Belum ada fitur. Klik tombol tambah fitur.';
            container.appendChild(emptyDiv);
        }
    }
</script>
@endpush

@endsection