@extends('index')

@section('title', 'Upgrade Paket')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('dashboard') }}"
                class="flex items-center text-blue-600 hover:text-blue-800 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Upgrade Paket Anda</h1>
            <p class="text-lg text-gray-600">Pilih paket yang lebih sesuai dengan kebutuhan pesantren Anda</p>
        </div>

        <!-- Current Plan -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border-2 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">Paket Saat Ini</span>
                    <h3 class="text-xl font-bold text-gray-900 mt-2">{{ $currentPlan->name }}</h3>
                    <p class="text-gray-600">{{ $currentPlan->description }}</p>
                    <div class="mt-2">
                        <span class="text-lg font-semibold text-gray-900">
                            Rp {{ number_format($currentSubscription->billing_cycle === 'yearly' ? $currentPlan->price_year : $currentPlan->price_month, 0, ',', '.') }}
                        </span>
                        <span class="text-gray-500">/{{ $currentSubscription->billing_cycle === 'yearly' ? 'tahun' : 'bulan' }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Berlaku hingga</p>
                    <p class="font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($currentSubscription->current_period_end)->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Available Plans -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            @foreach($availablePlans as $plan)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition">
                <!-- Plan Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                    <h3 class="text-xl font-bold mb-2">{{ $plan->name }}</h3>
                    <p class="text-green-100 text-sm">{{ $plan->description }}</p>
                </div>

                <!-- Pricing Options -->
                <div class="p-6">
                    <form action="{{ route('subscription.process-upgrade') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                        <!-- Billing Cycle Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Periode:</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative">
                                    <input type="radio" name="billing_cycle" value="monthly" class="sr-only peer" checked>
                                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50">
                                        <div class="font-semibold text-gray-900">Bulanan</div>
                                        <div class="text-lg font-bold text-green-600">
                                            Rp {{ number_format($plan->price_month, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="billing_cycle" value="yearly" class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50">
                                        <div class="font-semibold text-gray-900">Tahunan</div>
                                        <div class="text-lg font-bold text-green-600">
                                            Rp {{ number_format($plan->price_year, 0, ',', '.') }}
                                        </div>
                                        @if($plan->price_year > 0)
                                        <div class="text-xs text-green-500 mt-1">
                                            Hemat {{ number_format((($plan->price_month * 12) - $plan->price_year) / ($plan->price_month * 12) * 100, 0) }}%
                                        </div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Key Features -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-900 mb-3">Fitur Unggulan:</h4>
                            <ul class="space-y-2">
                                @foreach($plan->features->take(3) as $feature)
                                <li class="flex items-center text-sm">
                                    <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ str_replace('_', ' ', $feature->feature_key) }}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Upgrade Button -->
                        <button type="submit"
                            class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                            Upgrade ke {{ $plan->name }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Comparison Table -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Perbandingan Fitur</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 font-semibold text-gray-900">Fitur</th>
                            <th class="text-center py-3 font-semibold text-gray-900">{{ $currentPlan->name }}</th>
                            @foreach($availablePlans as $plan)
                            <th class="text-center py-3 font-semibold text-green-600">{{ $plan->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $allFeatures = collect();
                        foreach($availablePlans as $plan) {
                        $allFeatures = $allFeatures->merge($plan->features->pluck('feature_key'));
                        }
                        $allFeatures = $allFeatures->unique()->sort();
                        @endphp

                        @foreach($allFeatures as $feature)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 text-gray-700">{{ str_replace('_', ' ', $feature) }}</td>
                            <td class="text-center py-3">
                                @if(DB::table('plan_features')->where('plan_id', $currentPlan->id)->where('feature_key', $feature)->where('enabled', 1)->exists())
                                <svg class="h-5 w-5 text-green-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                @else
                                <svg class="h-5 w-5 text-gray-300 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                @endif
                            </td>
                            @foreach($availablePlans as $plan)
                            <td class="text-center py-3">
                                @if($plan->features->where('feature_key', $feature)->where('enabled', 1)->count())
                                <svg class="h-5 w-5 text-green-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                @else
                                <svg class="h-5 w-5 text-gray-300 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection