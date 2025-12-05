@extends('layouts.kelola_landing_ponpes')

@section('content')
    <div class="space-y-6">

        {{-- Header User --}}
        <div class="flex items-center space-x-4 mb-8">
            <img 
                src="/uploads/profile.jpg" 
                alt="User Profile" 
                class="h-10 w-10 rounded-full object-cover"
            />
            <span class="font-semibold text-gray-900 text-sm">AL Amal Batam</span>
        </div>

        {{-- ================= CAROUSEL ================= --}}
        <div class="border rounded p-4 flex justify-between items-start">
            <div>
                <h3 class="font-bold">Carousel</h3>
                <div class="grid grid-cols-3 gap-3 mt-2">
                    @forelse($carousels as $item)
                        <div class="border p-2 rounded text-center">
                            <img src="{{ asset('uploads/carousel/' . $item->image) }}" class="h-50 mx-auto">
                            <p class="text-sm font-semibold">{{ $item->title }}</p>
                            <p class="text-xs text-gray-500">{{ $item->subtitle }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 italic">Belum ada carousel</p>
                    @endforelse
                </div>
            </div>

            <button onclick="openModal('modalCarousel')" class="bg-green-600 text-white px-4 py-2 rounded">Tambah</button>
        </div>

        {{-- ================= ABOUT ================= --}}
        <div class="border rounded p-4 flex justify-between items-start">
            <div class="w-full">
                <h3 class="font-bold mb-4">About (Founder & Kepala Yayasan)</h3>

                @if($abouts)
                    {{-- FOUNDER --}}
                    <div class="mb-6">
                        <h2 class="text-lg font-bold">{{ $abouts->founder_name }}</h2>
                        <p class="text-gray-600">{{ $abouts->founder_position }}</p>
                        <p class="text-sm mt-2">{{ $abouts->founder_description }}</p>

                        @if($abouts->founder_image)
                            <img src="{{ asset('uploads/about/' . $abouts->founder_image) }}"
                                class="mt-2 rounded border"
                                width="120">
                        @endif
                    </div>

                    <hr class="my-4">

                    {{-- LEADER --}}
                    <div>
                        <h2 class="text-lg font-bold">{{ $abouts->leader_name }}</h2>
                        <p class="text-gray-600">{{ $abouts->leader_position }}</p>
                        <p class="text-sm mt-2">{{ $abouts->leader_description }}</p>

                        @if($abouts->leader_image)
                            <img src="{{ asset('uploads/about/' . $abouts->leader_image) }}"
                                class="mt-2 rounded border"
                                width="120">
                        @endif
                    </div>

                @else
                    <p class="text-gray-400 italic">Belum ada data About</p>
                @endif
            </div>

            <button onclick="openModal('modalAbout')" class="bg-green-600 text-white px-4 py-2 rounded">
                Edit
            </button>
        </div>

        {{-- ================= GALLERY ================= --}}
        <div class="border rounded p-4 flex justify-between items-start">
            <div class="w-full">
                <h3 class="font-bold">Gallery</h3>
                <div class="grid grid-cols-4 gap-3 mt-3">
                    @forelse($galleries as $img)
                        <div class="border rounded overflow-hidden">
                            <img src="{{ asset('uploads/gallery/'.$img->image) }}" class="h-full w-full object-cover">
                        </div>
                    @empty
                        <p class="text-gray-400 italic">Belum ada gallery</p>
                    @endforelse
                </div>
            </div>

            <div class="ml-4 flex-shrink-0">
                <button onclick="openModal('modalGallery')" class="bg-green-600 text-white px-4 py-2 rounded">Tambah</button>
            </div>
        </div>
    </div>

    {{-- ================= FOOTER ================= --}}
    <div class="border rounded p-4 flex justify-between items-start mt-6">
        <div class="w-full">
            <h3 class="font-bold mb-2">Footer</h3>

            @if($footers)
                <div class="space-y-1">
                    <p><strong>Instagram:</strong> {{ $footers->instagram }}</p>
                    <p><strong>Whatsapp:</strong> {{ $footers->whatsapp }}</p>
                    <p><strong>Alamat:</strong> {{ $footers->alamat }}</p>
                    <p><strong>Copyright:</strong> {{ $footers->copyright }}</p>

                    @if($footers->logo)
                        <p class="mt-2"><strong>Logo:</strong></p>
                        <img src="{{ asset('uploads/footer/'.$footers->logo) }}" class="h-16">
                    @endif
                </div>
            @else
                <p class="text-gray-400 italic mt-3">Belum ada data footer.</p>
            @endif
        </div>

        <button onclick="openModal('modalFooter')" class="bg-green-600 text-white px-4 py-2 rounded">
            Kelola
        </button>
    </div>

    {{-- ================= MODAL CAROUSEL ================= --}}
    <div id="modalCarousel" class="modal">
        <form id="formCarousel" class="modal-box" enctype="multipart/form-data">
            <div id="carouselError"
                class="p-3 mb-2 text-sm bg-red-100 text-red-600 rounded hidden">
            </div>
            <h2 class="modal-title mb-3">Tambah Carousel</h2>

            <label class="text-sm">Gambar (jpg/png)</label>
            <input type="file" name="image" accept="image/*" required onchange="previewImg(this, 'carouselPreview')">

            {{-- PREVIEW GAMBAR --}}
            <img id="carouselPreview" class="w-40 mt-2 rounded border hidden">

            <label class="text-sm mt-3">Judul</label>
            <input type="text" name="title" placeholder="Judul" class="mt-1">

            <label class="text-sm mt-3">Subtitle</label>
            <input type="text" name="subtitle" placeholder="Subtitle" class="mt-1">

            <div class="modal-action">
                <button type="button" onclick="closeModal('modalCarousel')" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Tambah</button>
            </div>
        </form>
    </div>

    {{-- ================= MODAL ABOUT ================= --}}
    <div id="modalAbout" class="modal hidden fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-[800px]">

            <h2 class="text-xl font-bold mb-4">Edit About</h2>

            <form id="formAbout" enctype="multipart/form-data">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- ==================== FOUNDER ==================== --}}
                    <div>
                        <h3 class="font-bold mb-2">Founder</h3>

                        <label>Nama Founder</label>
                        <input type="text" name="founder_name" class="input"
                            value="{{ $abouts->founder_name }}">

                        <label class="mt-2">Jabatan Founder</label>
                        <input type="text" name="founder_position" class="input"
                            value="{{ $abouts->founder_position }}">

                        <label class="mt-2">Deskripsi Founder</label>
                        <textarea name="founder_description" class="input">{{ $abouts->founder_description }}</textarea>

                        <label class="mt-2">Foto Founder</label><br>
                        <img id="previewFounder"
                            src="{{ asset('uploads/about/' . $abouts->founder_image) }}"
                            class="w-24 h-24 object-cover rounded mb-2">

                        <input type="file" name="founder_image" onchange="previewImg(this, 'previewFounder')">
                    </div>


                    {{-- ==================== LEADER ==================== --}}
                    <div>
                        <h3 class="font-bold mb-2">Kepala Yayasan (Leader)</h3>

                        <label>Nama Leader</label>
                        <input type="text" name="leader_name" class="input"
                            value="{{ $abouts->leader_name }}">

                        <label class="mt-2">Jabatan Leader</label>
                        <input type="text" name="leader_position" class="input"
                            value="{{ $abouts->leader_position }}">

                        <label class="mt-2">Deskripsi Leader</label>
                        <textarea name="leader_description" class="input">{{ $abouts->leader_description }}</textarea>

                        <label class="mt-2">Foto Leader</label><br>
                        <img id="previewLeader"
                            src="{{ asset('uploads/about/' . $abouts->leader_image) }}"
                            class="w-24 h-24 object-cover rounded mb-2">

                        <input type="file" name="leader_image" onchange="previewImg(this, 'previewLeader')">
                    </div>

                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded"
                        onclick="closeModal('modalAbout')">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- ================= MODAL GALLERY ================= --}}
    <div id="modalGallery" class="modal">
        <form id="formGallery" class="modal-box" enctype="multipart/form-data">

            <div id="galleryError"
                class="p-3 mb-2 text-sm bg-red-100 text-red-600 rounded hidden"></div>

            <h2 class="modal-title mb-3">Tambah Foto Gallery</h2>

            <label class="text-sm">Pilih Gambar</label>
            <input type="file" name="image" accept="image/*" required onchange="previewImg(this, 'galleryPreview')">

            <img id="galleryPreview" class="w-40 mt-2 rounded border hidden">

            <div class="modal-action">
                <button type="button" onclick="closeModal('modalGallery')" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Tambah</button>
            </div>
        </form>
    </div>

    {{-- ================= MODAL FOOTER ================= --}}
    <div id="modalFooter" class="modal">
        <form id="formFooter" class="modal-box" enctype="multipart/form-data">
            <h2 class="modal-title">Kelola Footer</h2>

            <label class="font-semibold">Logo</label>
            <input type="file" name="logo" id="logoInput" onchange="previewImg(this, 'logoPreview')">
            
            {{-- PREVIEW LOGO --}}
            <div class="mt-2">
                <p class="text-sm font-medium">Preview Logo:</p>
                <img id="logoPreview" 
                    src="{{ $footers->logo ? asset('uploads/footer/' . $footers->logo) : '' }}" 
                    class="w-50 mt-1 border rounded"
                >
            </div>

            <label>Instagram</label>
            <input type="text" name="instagram" value="{{ $footers->instagram ?? '' }}">

            <label>Whatsapp</label>
            <input type="text" name="whatsapp" value="{{ $footers->whatsapp ?? '' }}">

            <label>Alamat</label>
            <textarea name="alamat">{{ $footers->alamat ?? '' }}</textarea>

            <label>Copyright</label>
            <input type="text" name="copyright" value="{{ $footers->copyright ?? '' }}">

            <div class="modal-action">
                <button type="button" onclick="closeModal('modalFooter')" class="bg-gray-300 px-4 py-2 rounded">
                    Batal
                </button>
                <button class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>

{{-- ================= STYLE + SCRIPT ================= --}}
<style>
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 999;
}
.modal-box {
    background: #fff;
    padding: 18px;
    width: 520px;
    max-width: 95%;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.modal-title { font-weight: 700; font-size: 18px; }
.modal-action { display: flex; justify-content: flex-end; gap: 10px; margin-top:8px; }
input[type="text"], textarea { border:1px solid #ccc; padding:8px; border-radius:6px; width:100%; }
input[type="file"] { width:100%; }
textarea { min-height:100px; resize:vertical; }
</style>

<script>
const apiBase = "/api/landing";

// helper open/close modal
function openModal(id){ document.getElementById(id).style.display = 'flex' }
function closeModal(id){ document.getElementById(id).style.display = 'none' }

// --- form handlers ---

// CAROUSEL
document.getElementById('formCarousel').addEventListener('submit', async function(e){
    e.preventDefault();

    const errorBox = document.getElementById('carouselError');
    errorBox.classList.add('hidden');
    errorBox.innerHTML = '';

    const title = this.querySelector('input[name="title"]').value.trim();
    const subtitle = this.querySelector('input[name="subtitle"]').value.trim();
    const image = this.querySelector('input[name="image"]').files[0];

    if(!image || !title || !subtitle){
        let msg = '';
        if(!image) msg += '• Gambar wajib diisi<br>';
        if(!title) msg += '• Judul wajib diisi<br>';
        if(!subtitle) msg += '• Subtitle wajib diisi<br>';
        errorBox.innerHTML = msg;
        errorBox.classList.remove('hidden');
        return;
    }

    const fd = new FormData(this);
    const res = await fetch(apiBase + '/carousel', { method: 'POST', body: fd });
    const j = await res.json();

    alert(j.message || 'Selesai');
    if(res.ok) location.reload();
});

function previewImg(input, id) {
    const file = input.files[0];
    if (!file) return;

    const img = document.getElementById(id);
    img.src = URL.createObjectURL(file);
    img.classList.remove('hidden');
}

// ABOUT
document.getElementById('formAbout').addEventListener('submit', async function(e){
    e.preventDefault();

    const formData = new FormData(this);

    const response = await fetch('/api/landing/about/update', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    alert(result.message);

    location.reload();
});

// GALLERY
document.getElementById('formGallery').addEventListener('submit', async function(e){
    e.preventDefault();

    const errorBox = document.getElementById('galleryError');
    errorBox.classList.add('hidden');
    errorBox.innerHTML = '';

    const image = this.querySelector('input[name="image"]').files[0];

    if(!image){
        errorBox.innerHTML = '• Gambar wajib diisi';
        errorBox.classList.remove('hidden');
        return;
    }

    const fd = new FormData(this);
    const res = await fetch(apiBase + '/gallery', { 
        method: 'POST',
        body: fd 
    });
    const j = await res.json();

    alert(j.message);
    if(res.ok) location.reload();
});

// FOOTER
document.getElementById('formFooter').onsubmit = async function(e) {
    e.preventDefault();

    const fd = new FormData(this);

    const res = await fetch("/api/landing/footer", {
        method: "POST",
        body: fd
    });

    const json = await res.json();
    alert(json.message);
    location.reload();
};

function previewLogo(event) {
    const img = document.getElementById('logoPreview');
    img.src = URL.createObjectURL(event.target.files[0]);
}
</script>

@endsection