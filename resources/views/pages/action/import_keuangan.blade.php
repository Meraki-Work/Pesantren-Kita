@extends('index')

@section('title', 'Import Data Keuangan')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Import Data Keuangan</h1>
                    <p class="mt-1 text-sm text-gray-600">Import data keuangan dari file CSV</p>
                </div>
                <a href="{{ route('keuangan.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Error Alert -->
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Success Alert -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Form Import CSV</h3>
            </div>
            
            <form action="{{ route('keuangan.import.process') }}" method="POST" enctype="multipart/form-data" id="importForm" class="px-6 py-8">
                @csrf
                
                <!-- File Upload with Preview -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih File CSV <span class="text-red-500">*</span>
                    </label>
                    
                    <div id="uploadArea" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors cursor-pointer">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" id="uploadIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="csvFile" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span id="uploadText">Upload file CSV</span>
                                    <input id="csvFile" name="csv_file" type="file" class="sr-only" accept=".csv,.txt" required>
                                </label>
                                <p class="pl-1">maksimal 5MB</p>
                            </div>
                            <p id="fileInfo" class="text-xs text-gray-500">Format CSV dengan 6 kolom</p>
                            <p id="selectedFile" class="text-xs text-green-600 font-medium mt-2 hidden"></p>
                        </div>
                    </div>
                    
                    <!-- Error messages -->
                    <div id="fileError" class="mt-1 text-sm text-red-600 hidden"></div>
                    <div id="fileSizeError" class="mt-1 text-sm text-red-600 hidden"></div>
                    <div id="fileTypeError" class="mt-1 text-sm text-red-600 hidden"></div>
                    
                    <!-- Debug info -->
                    <div id="debugInfo" class="mt-2 text-xs text-gray-500 hidden">
                        <p>File name: <span id="debugFileName"></span></p>
                        <p>File size: <span id="debugFileSize"></span> bytes</p>
                        <p>File type: <span id="debugFileType"></span></p>
                    </div>
                </div>

                <!-- Options -->
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="skip_header" name="skip_header" value="1" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="skip_header" class="ml-2 text-sm font-medium text-gray-700">
                            Baris pertama adalah header (abaikan baris pertama)
                        </label>
                    </div>
                </div>

                <!-- Progress Bar (hidden by default) -->
                <div id="progressContainer" class="mb-6 hidden">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Upload progress</span>
                        <span id="progressPercent">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Format Info -->
                <div class="mb-8 p-4 bg-blue-50 rounded-lg">
                    <h4 class="text-md font-medium text-blue-800 mb-2">Format CSV yang Benar:</h4>
                    <div class="text-sm text-blue-700 space-y-1">
                        <p>• Kolom 1: <strong>Jumlah</strong> (contoh: 500000 atau 1,000,000)</p>
                        <p>• Kolom 2: <strong>Status</strong> (Masuk/Keluar/Pemasukan/Pengeluaran)</p>
                        <p>• Kolom 3: <strong>Tanggal</strong> (YYYY-MM-DD atau DD/MM/YYYY)</p>
                        <p>• Kolom 4: <strong>Kategori</strong> (opsional)</p>
                        <p>• Kolom 5: <strong>Sumber Dana</strong> (opsional)</p>
                        <p>• Kolom 6: <strong>Keterangan</strong> (opsional)</p>
                    </div>
                    <div class="mt-3">
                        <button type="button" onclick="downloadTemplate()" 
                           class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download Template CSV
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <div class="space-x-3">
                        <button type="button" onclick="resetForm()" 
                                class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Reset
                        </button>
                        <button type="submit" id="submitBtn"
                                class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg shadow-md hover:shadow-lg transition-all font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submitText">Import Data</span>
                            <span id="loadingSpinner" class="hidden ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Debug Modal -->
<div id="debugModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Debug Information</h3>
        <div class="text-sm text-gray-600 space-y-2">
            <p><strong>Form Action:</strong> <span id="debugAction"></span></p>
            <p><strong>CSRF Token:</strong> <span id="debugCsrf"></span></p>
            <p><strong>Form Method:</strong> <span id="debugMethod"></span></p>
            <p><strong>File Input Name:</strong> <span id="debugInputName"></span></p>
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeDebugModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                Close
            </button>
        </div>
    </div>
</div>

<script>
// Debug function
function showDebugInfo() {
    const form = document.getElementById('importForm');
    document.getElementById('debugAction').textContent = form.action;
    document.getElementById('debugCsrf').textContent = document.querySelector('input[name="_token"]')?.value || 'Not found';
    document.getElementById('debugMethod').textContent = form.method;
    document.getElementById('debugInputName').textContent = 'csv_file';
    document.getElementById('debugModal').classList.remove('hidden');
}

function closeDebugModal() {
    document.getElementById('debugModal').classList.add('hidden');
}

// File upload handling
document.getElementById('uploadArea').addEventListener('click', function() {
    document.getElementById('csvFile').click();
});

document.getElementById('csvFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const uploadText = document.getElementById('uploadText');
    const selectedFile = document.getElementById('selectedFile');
    const fileInfo = document.getElementById('fileInfo');
    const uploadIcon = document.getElementById('uploadIcon');
    
    // Reset error messages
    document.getElementById('fileError').classList.add('hidden');
    document.getElementById('fileSizeError').classList.add('hidden');
    document.getElementById('fileTypeError').classList.add('hidden');
    
    if (file) {
        // Validate file size (max 5MB)
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if (file.size > maxSize) {
            document.getElementById('fileSizeError').textContent = 'File terlalu besar. Maksimal 5MB.';
            document.getElementById('fileSizeError').classList.remove('hidden');
            e.target.value = '';
            return;
        }
        
        // Validate file type
        const allowedTypes = ['text/csv', 'text/plain', 'application/vnd.ms-excel'];
        if (!allowedTypes.includes(file.type) && !file.name.toLowerCase().endsWith('.csv')) {
            document.getElementById('fileTypeError').textContent = 'Hanya file CSV yang diperbolehkan.';
            document.getElementById('fileTypeError').classList.remove('hidden');
            e.target.value = '';
            return;
        }
        
        // Update UI
        uploadText.textContent = 'Ganti file';
        selectedFile.textContent = `✓ ${file.name} (${formatFileSize(file.size)})`;
        selectedFile.classList.remove('hidden');
        fileInfo.classList.add('hidden');
        uploadIcon.classList.add('text-green-500');
        
        // Show debug info
        document.getElementById('debugInfo').classList.remove('hidden');
        document.getElementById('debugFileName').textContent = file.name;
        document.getElementById('debugFileSize').textContent = file.size;
        document.getElementById('debugFileType').textContent = file.type || 'unknown';
        
        // Enable submit button
        document.getElementById('submitBtn').disabled = false;
        
        console.log('File selected:', {
            name: file.name,
            size: file.size,
            type: file.type,
            lastModified: file.lastModified
        });
    } else {
        resetFileUI();
    }
});

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Reset file UI
function resetFileUI() {
    document.getElementById('uploadText').textContent = 'Upload file CSV';
    document.getElementById('selectedFile').classList.add('hidden');
    document.getElementById('fileInfo').classList.remove('hidden');
    document.getElementById('uploadIcon').classList.remove('text-green-500');
    document.getElementById('debugInfo').classList.add('hidden');
    document.getElementById('submitBtn').disabled = true;
}

// Reset form
function resetForm() {
    document.getElementById('importForm').reset();
    resetFileUI();
    document.getElementById('progressContainer').classList.add('hidden');
    document.getElementById('progressBar').style.width = '0%';
    document.getElementById('progressPercent').textContent = '0%';
    console.log('Form reset');
}

// Form submission
document.getElementById('importForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('csvFile');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    // Validate file
    if (!fileInput.files || fileInput.files.length === 0) {
        e.preventDefault();
        document.getElementById('fileError').textContent = 'Silakan pilih file CSV terlebih dahulu.';
        document.getElementById('fileError').classList.remove('hidden');
        return;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.textContent = 'Memproses...';
    loadingSpinner.classList.remove('hidden');
    
    // Show progress bar
    document.getElementById('progressContainer').classList.remove('hidden');
    
    // Simulate progress
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += 10;
        document.getElementById('progressBar').style.width = progress + '%';
        document.getElementById('progressPercent').textContent = progress + '%';
        
        if (progress >= 90) {
            clearInterval(progressInterval);
        }
    }, 300);
    
    console.log('Submitting form with file:', fileInput.files[0].name);
});

// Download template
// Download template dengan format yang benar
function downloadTemplate() {
    // Format CSV yang benar untuk import
    const csvContent = "jumlah,status,tanggal,kategori,sumber_dana,keterangan\n" +
                      "1000000,Masuk,2024-01-15,Donatur,Donatur Tetap,Sumbangan bulanan dari Bpk. Ahmad\n" +
                      "500000,Keluar,2024-01-16,Operasional,Kas Pesantren,Pembelian beras dan bahan makanan\n" +
                      "750000,Masuk,2024-01-17,Iuran,Santri,Iuran bulan Januari 2024\n" +
                      "300000,Keluar,2024-01-18,Gaji,Guru,Gaji ustadz bulan Januari\n" +
                      "1500000,Masuk,2024-01-19,Investasi,Investor,Investasi pembangunan asrama\n" +
                      "200000,Keluar,2024-01-20,Listrik,PLN,Pembayaran listrik bulan Januari\n" +
                      "450000,Masuk,2024-01-21,Sumbangan,Yayasan,Sumbangan untuk kegiatan pesantren\n" +
                      "180000,Keluar,2024-01-22,Akademik,Peralatan,Beli buku dan alat tulis\n" +
                      "1200000,Masuk,2024-01-23,SPP,Orang Tua,SPP santri bulan Januari\n" +
                      "250000,Keluar,2024-01-24,Kesehatan,UKS,Obat-obatan dan perawatan";
    
    // Tambahkan BOM untuk UTF-8
    const BOM = "\uFEFF";
    
    // Create blob dengan BOM untuk encoding yang benar
    const blob = new Blob([BOM + csvContent], { 
        type: 'text/csv;charset=utf-8;' 
    });
    
    // Create download link
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.href = url;
    link.download = 'template_import_keuangan_pesantren.csv';
    link.style.display = 'none';
    
    document.body.appendChild(link);
    link.click();
    
    // Cleanup
    setTimeout(() => {
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }, 100);
    
    console.log('Template CSV downloaded with correct format');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Import form loaded');
    console.log('Route:', '{{ route("keuangan.import.process") }}');
    
    // Debug: Check form attributes
    const form = document.getElementById('importForm');
    console.log('Form attributes:', {
        action: form.action,
        method: form.method,
        enctype: form.enctype,
        hasFileInput: !!document.querySelector('input[type="file"]')
    });
});
</script>

<style>
#uploadArea:hover {
    border-color: #3b82f6;
    background-color: #f9fafb;
}

#uploadArea.drag-over {
    border-color: #10b981;
    background-color: #ecfdf5;
}

.hidden {
    display: none !important;
}
</style>
@endsection