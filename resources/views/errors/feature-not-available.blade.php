@extends('index')

@section('title', 'Fitur Tidak Tersedia')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="mt-6 text-2xl font-bold text-gray-900">
                    Fitur Tidak Tersedia
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    {{ $message }}
                </p>
                
                <!-- Informasi fitur yang diminta -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <strong>Fitur yang diminta:</strong> 
                        <span class="capitalize">{{ str_replace('_', ' ', $feature) }}</span>
                    </p>
                </div>

                <div class="mt-6 space-y-3">
                    <a href="{{ route('subscription.upgrade') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Upgrade Paket
                    </a>
                    <a href="{{ route('subscription.plans') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Lihat Semua Paket
                    </a>
                </div>
                <div class="mt-4">
                    <a href="{{ url()->previous() }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Kembali ke Halaman Sebelumnya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection