<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingContent;
use App\Models\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LandingContentController extends Controller
{
    /**
     * Get authenticated user's ponpes_id
     */
    private function getUserPonpesId()
    {
        $user = Auth::user();

        if (!$user || !$user->ponpes_id) {
            // Jika user tidak memiliki ponpes_id, throw exception atau redirect
            if ($user && ($user->role === 'admin' || $user->role === 'super_admin')) {
                // Admin bisa melihat semua data
                return null;
            }

            abort(403, 'User tidak memiliki pesantren yang ditugaskan.');
        }

        return $user->ponpes_id;
    }

    /**
     * Get user role
     */
    private function getUserRole()
    {
        return Auth::user()->role ?? null;
    }

    /**
     * Check if user can access all ponpes
     */
    private function canAccessAllPonpes()
    {
        $role = $this->getUserRole();
        return in_array($role, ['admin', 'super_admin']);
    }

    /**
     * Check ownership of landing content
     * Override parent method to handle LandingContent model
     */
    protected function checkOwnership($table, $id = null)
    {
        // Jika parameter pertama adalah instance LandingContent (backward compatibility)
        if ($table instanceof LandingContent) {
            $landingContent = $table;
        } 
        // Jika parameter adalah string (table name) dan id
        elseif (is_string($table) && $id) {
            $landingContent = LandingContent::find($id);
            if (!$landingContent) {
                abort(404, 'Konten tidak ditemukan');
            }
        }
        // Jika tidak valid
        else {
            abort(400, 'Parameter tidak valid');
        }

        $userPonpesId = $this->getUserPonpesId();
        $canAccessAll = $this->canAccessAllPonpes();

        // Jika user bisa akses semua ponpes (admin/super_admin), izinkan akses
        if ($canAccessAll) {
            return true;
        }

        // Jika user biasa, cek apakah ponpes_id cocok
        return $userPonpesId && $landingContent->ponpes_id == $userPonpesId;
    }

    /**
     * Helper method untuk check ownership dengan instance LandingContent
     */
    private function checkLandingContentOwnership(LandingContent $landingContent)
    {
        return $this->checkOwnership($landingContent);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userPonpesId = $this->getUserPonpesId();
        $contentType = $request->query('content_type');
        $filterPonpesId = $request->query('ponpes_id');

        // Query dasar
        $query = LandingContent::with('ponpes');

        // Jika user biasa, hanya bisa akses ponpesnya sendiri
        if (!$this->canAccessAllPonpes()) {
            if ($userPonpesId) {
                $query->where('ponpes_id', $userPonpesId);
            } else {
                // User biasa tanpa ponpes_id tidak bisa melihat data
                $contents = collect(); // Empty collection
                $ponpesList = collect();
                $userPonpes = null;

                return view('admin.landing-content.index', compact('contents', 'userPonpes'))->with('error', 'Anda belum ditugaskan ke pesantren manapun.');
            }
        }
        // Jika admin/super admin dan memilih filter ponpes tertentu
        elseif ($filterPonpesId) {
            $query->where('ponpes_id', $filterPonpesId);
        }
        // Jika admin/super admin tanpa filter, tampilkan semua (atau batasan tertentu)

        if ($contentType) {
            $query->where('content_type', $contentType);
        }

        $contents = $query
            ->orderBy('ponpes_id')
            ->orderBy('display_order')
            ->limit(100) 
            ->get();

        // Get ponpes list untuk dropdown filter
        if ($this->canAccessAllPonpes()) {
            // Admin bisa melihat semua ponpes aktif
            $ponpesList = Ponpes::where('status', 'Aktif')->get();
        } else {
            // User biasa hanya bisa melihat ponpes mereka sendiri
            $ponpesList = Ponpes::where('id_ponpes', $userPonpesId)->get();
        }

        // Get user's ponpes
        $userPonpes = Ponpes::find($userPonpesId);

        // Stats untuk dashboard - berdasarkan filter yang aktif
        $statsQuery = LandingContent::query();

        if (!$this->canAccessAllPonpes() && $userPonpesId) {
            $statsQuery->where('ponpes_id', $userPonpesId);
        } elseif ($filterPonpesId) {
            $statsQuery->where('ponpes_id', $filterPonpesId);
        }

        $stats = [
            'total' => $contents->count(),
            'active' => $statsQuery->clone()->where('is_active', true)->count(),
            'with_images' => $statsQuery->clone()->whereNotNull('image')->count(),
            'carousel_count' => $statsQuery->clone()->where('content_type', 'carousel')->count(),
            'founder_count' => $statsQuery->clone()->where('content_type', 'about_founder')->count(),
            'leader_count' => $statsQuery->clone()->where('content_type', 'about_leader')->count(),
        ];

        return view('admin.landing-content.index', compact('contents', 'ponpesList', 'stats', 'userPonpes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userPonpesId = $this->getUserPonpesId();

        if (!$this->canAccessAllPonpes() && !$userPonpesId) {
            return redirect()->route('admin.landing-content.index')
                ->with('error', 'Anda belum ditugaskan ke pesantren. Silakan hubungi administrator.');
        }

        if ($this->canAccessAllPonpes()) {
            // Admin bisa memilih ponpes mana
            $ponpesList = Ponpes::where('status', 'Aktif')->get();
        } else {
            // User biasa hanya ponpes mereka
            $ponpesList = Ponpes::where('id_ponpes', $userPonpesId)->get();
        }

        return view('admin.landing-content.create', compact('ponpesList'));
    }

    /**
     * Show form berdasarkan tipe konten
     */
    public function createByType(Request $request, $type)
    {
        $user = Auth::user();

        // List pesantren (untuk admin & super admin)
        $ponpesList = Ponpes::all();

        // Tentukan ponpes_id yang dipakai
        if ($this->canAccessAllPonpes()) {
            $ponpesId = $request->get('ponpes_id')
                ?? optional($ponpesList->first())->id_ponpes;
        } else {
            $ponpesId = $user->ponpes_id;
        }

        // Hitung urutan tampil berikutnya
        $nextOrder = $ponpesId
            ? $this->getNextDisplayOrder($ponpesId)
            : 1;

        return view("admin.landing-content.create-{$type}", [
            'ponpesList' => $ponpesList,
            'type'       => $type,
            'nextOrder'  => $nextOrder,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'ponpes_id'      => 'required|exists:ponpes,id_ponpes',
            'content_type' => [
                'required',
                Rule::in([
                    'carousel',
                    'about_founder',
                    'about_leader',
                    'about_vision',
                    'about_mission',
                    'program_list',
                    'gallery',
                    'testimony',
                    'cta'
                ])
            ],
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'image'          => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'display_order'  => 'nullable|integer|min:1',
            'url'            => 'nullable|url',
            'is_active'      => 'nullable|boolean',
        ]);

        // Pastikan is_active boolean
        $validated['is_active'] = $request->has('is_active');

        // AUTO DISPLAY ORDER (FALLBACK AMAN)
        if (empty($validated['display_order']) || $validated['display_order'] < 1) {
            $validated['display_order'] = $this->getNextDisplayOrder(
                $validated['ponpes_id']
            );
        }

        // UPLOAD IMAGE
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('landing-content', 'public');
        }

        LandingContent::create($validated);

        return redirect()
            ->route('admin.landing-content.index')
            ->with('success', 'Konten berhasil ditambahkan');
    }

    private function getNextDisplayOrder($ponpesId)
    {
        return (LandingContent::where('ponpes_id', $ponpesId)
            ->max('display_order') ?? 0) + 1;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $landingContent = LandingContent::findOrFail($id);
        
        // Check ownership
        if (!$this->checkOwnership('landing_contents', $id)) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.landing-content.show', compact('landingContent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $landingContent = LandingContent::findOrFail($id);
        
        // Check ownership
        if (!$this->checkOwnership('landing_contents', $id)) {
            abort(403, 'Unauthorized action.');
        }

        // Provide content types list for the edit form (value => label)
        $contentTypes = [
            'carousel' => 'Carousel',
            'about_founder' => 'Tentang Pendiri',
            'about_leader' => 'Tentang Pimpinan',
            'about_vision' => 'Visi',
            'about_mission' => 'Misi',
            'program_list' => 'Daftar Program',
            'gallery' => 'Galeri',
            'testimony' => 'Testimoni',
            'cta' => 'CTA'
        ];

        return view('admin.landing-content.edit', compact('landingContent', 'contentTypes'));
    }

    /**
     * Show form edit berdasarkan tipe konten
     */
    public function editByType($id, $type)
    {
        $landingContent = LandingContent::findOrFail($id);
        
        // Check ownership
        if (!$this->checkOwnership('landing_contents', $id)) {
            abort(403, 'Unauthorized action.');
        }

        $validTypes = [
            'carousel',
            'about_founder',
            'about_leader',
            'about_vision',
            'about_mission',
            'program_list',
            'gallery',
            'testimony',
            'cta'
        ];

        // Normalize for comparison so legacy 'about_mision' matches canonical 'about_mission'
        $normalizedType = $type === 'about_mision' ? 'about_mission' : $type;
        $landingType = $landingContent->content_type === 'about_mision' ? 'about_mission' : $landingContent->content_type;

        if (!in_array($type, $validTypes) || $landingType !== $normalizedType) {
            return redirect()->route('admin.landing-content.edit', $id)
                ->with('error', 'Tipe konten tidak sesuai');
        }

        return view("admin.landing-content.edit-{$type}", compact('landingContent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $landingContent = LandingContent::findOrFail($id);
        
        // Check ownership
        if (!$this->checkOwnership('landing_contents', $id)) {
            abort(403, 'Unauthorized action.');
        }

        $userPonpesId = $this->getUserPonpesId();

        $validContentTypes = [
            'carousel',
            'about_founder', 
            'about_leader',
            'about_vision',
            'about_mission',
            'program_list',
            'gallery',
            'testimony',
            'cta'
        ];

        $rules = [
            'content_type' => ['required', Rule::in($validContentTypes)],
            'is_active' => 'nullable|boolean',
        ];

        // Tambahkan rules berdasarkan tipe konten
        // Normalize legacy misspelling for update as well
        $contentType = $request->content_type;
        if ($contentType === 'about_mision') {
            $contentType = 'about_mission';
            $request->merge(['content_type' => $contentType]);
        }
        
        switch ($contentType) {
            case 'carousel':
                $rules['title'] = 'nullable|string|max:255';
                $rules['subtitle'] = 'nullable|string';
                $rules['description'] = 'nullable|string';
                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $rules['url'] = 'nullable|url|max:255';
                $rules['display_order'] = 'required|integer|min:1';
                break;

            case 'about_founder':
            case 'about_leader':
                $rules['title'] = 'required|string|max:255';
                $rules['position'] = 'required|string|max:100';
                $rules['description'] = 'required|string|min:10';
                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $rules['url'] = 'nullable|url|max:255';
                $rules['display_order'] = 'nullable|integer|min:0';
                break;

            case 'about_vision':
            case 'about_mission':  
            case 'program_list':
                $rules['title'] = 'required|string|max:255';
                $rules['description'] = 'required|string|min:10';
                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $rules['url'] = 'nullable|url|max:255';
                $rules['display_order'] = 'required|integer|min:1';
                break;

            case 'gallery':
                $rules['title'] = 'required|string|max:255';
                $rules['description'] = 'nullable|string';
                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $rules['position'] = 'nullable|string|max:100';
                $rules['url'] = 'nullable|url|max:255';
                $rules['display_order'] = 'required|integer|min:1';
                break;

            case 'testimony':
                $rules['title'] = 'required|string|max:255';
                $rules['position'] = 'nullable|string|max:100';
                $rules['description'] = 'required|string|min:10';
                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $rules['url'] = 'nullable|url|max:255';
                $rules['display_order'] = 'required|integer|min:1';
                break;

            case 'cta':
                $rules['title'] = 'required|string|max:255';
                $rules['subtitle'] = 'nullable|string';
                $rules['description'] = 'nullable|string';
                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $rules['url'] = 'required|url|max:255';
                $rules['position'] = 'nullable|string|max:100';
                $rules['display_order'] = 'required|integer|min:1';
                break;
        }

        $validated = $request->validate($rules);

        // Normalize stored content_type
        if (isset($validated['content_type']) && $validated['content_type'] === 'about_mision') {
            $validated['content_type'] = 'about_mission';
        }

        // Set ponpes_id dari user yang login (tidak boleh diubah)
        $validated['ponpes_id'] = $userPonpesId;

        // Handle image upload
        $imageTypes = ['carousel', 'about_founder', 'about_leader', 'gallery', 'testimony', 'cta', 'about_vision', 'about_mission', 'program_list'];

        if (in_array($contentType, $imageTypes) && $request->hasFile('image')) {
            // Delete old image if exists
            if ($landingContent->image && Storage::disk('public')->exists($landingContent->image)) {
                Storage::disk('public')->delete($landingContent->image);
            }

            $imagePath = $request->file('image')->store('landing-content', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_active'] = $request->has('is_active');

        try {
            $landingContent->update($validated);

            $contentTypeNames = [
                'carousel' => 'Carousel',
                'about_founder' => 'Founder/Pendiri',
                'about_leader' => 'Leader/Pengurus',
                'about_vision' => 'Visi',
                'about_mission' => 'Misi',
                'program_list' => 'Program',
                'gallery' => 'Galeri',
                'testimony' => 'Testimoni',
                'cta' => 'Call to Action',
            ];

            return redirect()->route('admin.landing-content.index')
                ->with('success', $contentTypeNames[$contentType] . ' berhasil diperbarui.');
                
        } catch (\Exception $e) {
            \Log::error('Error updating landing content: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $landingContent = LandingContent::where('id_content', $id)->firstOrFail();

        // Check ownership
        if (!$this->checkLandingContentOwnership($landingContent)) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Hapus gambar jika ada
            if ($landingContent->image && Storage::disk('public')->exists($landingContent->image)) {
                Storage::disk('public')->delete($landingContent->image);
            }

            $contentTypeNames = [
                'carousel' => 'Carousel',
                'about_founder' => 'Founder/Pendiri',
                'about_leader' => 'Leader/Pengurus',
                'about_vision' => 'Visi',
                'about_mission' => 'Misi',
                'program_list' => 'Program',
                'gallery' => 'Galeri',
                'testimony' => 'Testimoni',
                'cta' => 'Call to Action',
            ];

            $contentTypeName = $contentTypeNames[$landingContent->content_type] 
                ?? $landingContent->content_type;

            $landingContent->delete();

            return response()->json([
                'success' => true,
                'message' => $contentTypeName . ' berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting landing content: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data'
            ], 500);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Request $request, $id)
    {
        $landingContent = LandingContent::where('id_content', $id)->firstOrFail();

        if (!$this->checkOwnership($landingContent)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $newActive = (int) $request->input('is_active') === 1;

            $landingContent->update([
                'is_active' => $newActive
            ]);

            return response()->json([
                'success' => true,
                'is_active' => $newActive
            ]);

        } catch (\Throwable $e) {
            \Log::error('Toggle status error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

    /**
     * Return content detail as JSON for client-side verification
     */
    public function getContentDetail($id)
    {
        $landingContent = LandingContent::findOrFail($id);

        // Check ownership
        if (!$this->checkOwnership('landing_contents', $id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id_content' => $landingContent->id_content,
            'is_active' => $landingContent->is_active ? 1 : 0,
            'display_order' => $landingContent->display_order,
            'title' => $landingContent->title,
        ]);
    }

    /**
     * Reorder display order
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        try {
            foreach ($request->items as $item) {

                if (
                    !isset($item['id']) ||
                    !isset($item['order']) ||
                    !is_numeric($item['order'])
                ) {
                    continue;
                }

                $updateData = [
                    'display_order' => (int) $item['order'],
                ];

                if (isset($item['is_active'])) {
                    $updateData['is_active'] = (int) $item['is_active'] === 1;
                }

                LandingContent::where('id_content', $item['id'])
                    ->update($updateData);
            }

            return response()->json([
                'success' => true
            ]);

        } catch (\Throwable $e) {
            \Log::error('REORDER ERROR: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

}