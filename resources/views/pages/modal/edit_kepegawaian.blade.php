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
                <form action="{{ route('kepegawaian.update', $user->id_user) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                                    {{-- Use consistent lowercase values so DB and comparisons are predictable --}}
                                    <option value="aktif" {{ strtolower($user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak aktif" {{ strtolower($user->status) == 'tidak aktif' ? 'selected' : '' }}>Tidak aktif</option>
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

                    <form method="POST" action="{{ route('kepegawaian.destroy', $user->id_user) }}">
                        @csrf
                        @method('DELETE')

                        <div class="flex justify-end gap-4">
                            <button type="button" @click="open = false" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">Batal</button>
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">Hapus</button>
                        </div>
                    </form>
                </div>
            </template>

        </div>
    </div>
</div>
