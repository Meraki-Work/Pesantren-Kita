@extends('index')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Tambah Data Keuangan</h2>
            </div>
            
            <form action="{{ route('keuangan.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <input type="number" 
                               name="jumlah" 
                               id="jumlah"
                               step="0.01"
                               required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="id_kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="id_kategori" 
                                id="id_kategori"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategories as $kategori)
                                <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                        <input type="text" 
                               name="sumber_dana" 
                               id="sumber_dana"
                               required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" 
                                id="status"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Status</option>
                            <option value="Masuk">Pemasukan</option>
                            <option value="Keluar">Pengeluaran</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" 
                               name="tanggal" 
                               id="tanggal"
                               required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" 
                                  id="keterangan"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('keuangan.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-200">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection