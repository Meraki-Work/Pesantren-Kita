<div 
    x-data="{
        open: false,
        mode: '', 
        user: @js($user)
    }"
    x-cloak

    x-on:open-edit-{{ $user->id_user }}.window="
        mode = 'edit';
        open = true;
    "

    x-on:open-delete-{{ $user->id_user }}.window="
        mode = 'delete';
        open = true;
    "

    x-on:close-edit.window="open = false"
    x-on:close-delete.window="open = false"
>

    <!-- Overlay (Z-index tinggi) -->
    <div 
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[9998]"
        @click="open = false"
    ></div>

    <!-- Modal (di atas overlay 100% pasti menutupi dropdown) -->
    <div 
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-center justify-center z-[9999]"
    >
        <div class="bg-white rounded-xl w-[700px] p-7 relative shadow-lg z-[10000]">

            <!-- Close -->
            <button 
                class="absolute right-3 top-3 text-gray-600 text-xl"
                @click="open = false"
            >&times;</button>

            <!-- Judul Edit -->
            <template x-if="mode === 'edit'">
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Edit Pegawai
                </h2>
            </template>

            <!-- Judul Delete -->
            <template x-if="mode === 'delete'">
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22"/>
                    </svg>
                    Hapus Pegawai
                </h2>
            </template>

            <!-- FORM EDIT -->
            <template x-if="mode === 'edit'">
                <form id="edit-form-{{ $user->id_user }}" @submit.prevent="submitEdit({{ $user->id_user }})">
                    @csrf

                    <div class="grid grid-cols-2 gap-5 mb-4">
                        <div>
                            <label class="text-sm font-semibold">Username</label>
                            <input type="text" name="username" value="{{ $user->username }}" class="w-full bg-gray-200 p-2 rounded-lg mt-1" required>
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-gray-200 p-2 rounded-lg mt-1" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5 mb-4">
                        <div>
                            <label class="text-sm font-semibold">Role</label>
                            {{-- Gunakan select untuk menghindari typo: hanya Admin atau Pengajar --}}
                            <select name="role" class="w-full bg-gray-200 p-2 rounded-lg mt-1" required>
                                <option value="Admin" {{ strtolower($user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Pengajar" {{ strtolower($user->role) === 'pengajar' ? 'selected' : '' }}>Pengajar</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Status</label>
                                <select name="status" class="w-full bg-gray-200 p-2 rounded-lg mt-1">
                                    {{-- Use enum values from database --}}
                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Tidak aktif</option>
                                </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button type="button" @click="open = false" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">Batal</button>
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Simpan</button>
                    </div>
                </form>
            </template>

            <!-- FORM DELETE -->
            <template x-if="mode === 'delete'">
                <div>
                    <p class="mb-6 text-gray-700">
                        Apakah Anda yakin ingin menghapus pegawai <strong>{{ $user->username }}</strong>?
                    </p>

                    <div class="flex justify-end gap-4">
                        <button type="button" @click="open = false" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">Batal</button>
                        <button type="button" @click="submitDelete({{ $user->id_user }})" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">Hapus</button>
                    </div>
                </div>
            </template>

        </div>
    </div>
</div>

<script>
// Fungsi helper untuk mendapatkan CSRF token
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

// AJAX handler untuk Edit
window.submitEdit = async function(userId) {
    const form = document.getElementById(`edit-form-${userId}`);
    const formData = new FormData(form);
    const csrfToken = getCsrfToken();

    try {
        const response = await fetch(`/api/kepegawaian/${userId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                username: formData.get('username'),
                email: formData.get('email'),
                role: formData.get('role'),
                status: formData.get('status')
            })
        });

        const data = await response.json();

        if (response.ok) {
            // Tampilkan success message dan refresh halaman
            alert('Data berhasil diupdate');
            window.location.href = data.redirect;
        } else {
            alert('Error: ' + (data.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate data');
    }
};

// AJAX handler untuk Delete
window.submitDelete = async function(userId) {
    if (!confirm('Apakah Anda benar-benar ingin menghapus pegawai ini?')) {
        return;
    }

    const csrfToken = getCsrfToken();

    try {
        const response = await fetch(`/api/kepegawaian/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok) {
            // Tampilkan success message dan refresh halaman
            alert('Data berhasil dihapus');
            window.location.href = data.redirect;
        } else {
            alert('Error: ' + (data.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus data');
    }
};
</script>
