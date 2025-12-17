@extends('index')

@section('title', 'Paket Langganan')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <a href="{{ route('dashboard') }}"
                class="flex items-center text-blue-600 hover:text-blue-800 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Pilih Paket yang Tepat untuk Anda</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Tingkatkan pengelolaan pesantren Anda dengan paket yang sesuai kebutuhan
            </p>
        </div>

        <!-- Current Subscription Info -->
        @if($currentSubscription)
        @php
        $currentPlan = DB::table('plans')->where('id', $currentSubscription->plan_id)->first();
        @endphp
        <div class="mb-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-blue-900">Paket Anda Saat Ini</h3>
                    <p class="text-blue-700">{{ $currentPlan->name }} -
                        {{ $currentSubscription->billing_cycle === 'yearly' ? 'Tahunan' : 'Bulanan' }}
                    </p>
                    <p class="text-sm text-blue-600">
                        Berlaku hingga: {{ \Carbon\Carbon::parse($currentSubscription->current_period_end)->format('d M Y') }}
                    </p>
                </div>
                <a href="{{ route('subscription.upgrade') }}"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Upgrade
                </a>
            </div>
        </div>
        @endif

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            @foreach($plans as $plan)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden 
                {{ $currentSubscription && $currentSubscription->plan_id == $plan->id ? 'ring-2 ring-blue-500' : '' }}">

                <!-- Plan Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                    <h3 class="text-xl font-bold mb-2">{{ $plan->name }}</h3>
                    <p class="text-blue-100 text-sm mb-4">{{ $plan->description }}</p>

                    <!-- Pricing -->
                    <div class="flex items-baseline mb-4">
                        <span class="text-3xl font-bold">
                            Rp {{ number_format($plan->price_month, 0, ',', '.') }}
                        </span>
                        <span class="text-blue-200 ml-2">/bulan</span>
                    </div>
                    @if($plan->price_year > 0)
                    <div class="text-blue-200 text-sm">
                        Rp {{ number_format($plan->price_year, 0, ',', '.') }} /tahun
                        <span class="text-green-300">(Hemat {{ number_format((($plan->price_month * 12) - $plan->price_year) / ($plan->price_month * 12) * 100, 0) }}%)</span>
                    </div>
                    @endif
                </div>

                <!-- Features -->
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Fitur yang Didapat:</h4>
                    <ul class="space-y-3">
                        @foreach($plan->features as $feature)
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">{{ str_replace('_', ' ', $feature->feature_key) }}</span>
                        </li>
                        @endforeach

                        <!-- Limits -->
                        @if(!empty($plan->limits))
                        @foreach($plan->limits as $key => $limit)
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">
                                {{ str_replace('_', ' ', $key) }}:
                                {{ $limit === -1 ? 'Unlimited' : $limit }}
                            </span>
                        </li>
                        @endforeach
                        @endif
                    </ul>

                    <!-- Action Button -->
                    <div class="mt-8">
                        @if($currentSubscription && $currentSubscription->plan_id == $plan->id)
                        <button class="w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed">
                            Paket Saat Ini
                        </button>
                        @elseif(!$currentSubscription || $currentSubscription->plan_id != $plan->id)
                        <form action="{{ route('subscription.process-upgrade') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <input type="hidden" name="billing_cycle" value="monthly">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                                {{ $currentSubscription ? 'Upgrade' : 'Berlangganan' }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Pertanyaan Umum</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara upgrade paket?</h4>
                    <p class="text-gray-600">Anda bisa upgrade kapan saja. Perubahan akan langsung aktif setelah pembayaran berhasil.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Apakah bisa downgrade?</h4>
                    <p class="text-gray-600">Downgrade hanya bisa dilakukan di akhir periode billing saat ini.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Metode pembayaran apa yang tersedia?</h4>
                    <p class="text-gray-600">Transfer bank, virtual account, dan e-wallet (OVO, Gopay, Dana).</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Apakah ada garansi uang kembali?</h4>
                    <p class="text-gray-600">Kami menawarkan garansi 14 hari uang kembali jika tidak puas.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection