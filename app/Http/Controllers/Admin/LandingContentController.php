<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingContent;
use App\Models\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

                return view('admin.landing-content.index', compact('contents'))->with('error', 'Anda belum ditugaskan ke pesantren manapun.');
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

        $contents = $query->orderBy('ponpes_id')
            ->orderBy('content_type')
            ->orderBy('display_order')
            ->paginate(10);

        // Get ponpes list untuk dropdown filter
        if ($this->canAccessAllPonpes()) {
            // Admin bisa melihat semua ponpes aktif
            $ponpesList = Ponpes::where('status', 'Aktif')->get();
        } else {
            // User biasa hanya bisa melihat ponpes mereka sendiri
            $ponpesList = Ponpes::where('id_ponpes', $userPonpesId)->get();
        }

        // Stats untuk dashboard - berdasarkan filter yang aktif
        $statsQuery = LandingContent::query();

        if (!$this->canAccessAllPonpes() && $userPonpesId) {
            $statsQuery->where('ponpes_id', $userPonpesId);
        } elseif ($filterPonpesId) {
            $statsQuery->where('ponpes_id', $filterPonpesId);
        }

        $stats = [
            'total' => $contents->total(),
            'active' => $statsQuery->clone()->where('is_active', true)->count(),
            'with_images' => $statsQuery->clone()->whereNotNull('image')->count(),
            'carousel_count' => $statsQuery->clone()->where('content_type', 'carousel')->count(),
            'founder_count' => $statsQuery->clone()->where('content_type', 'about_founder')->count(),
            'leader_count' => $statsQuery->clone()->where('content_type', 'about_leader')->count(),
        ];

        return view('admin.landing-content.index-card', compact('contents', 'ponpesList', 'stats'));
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
        $userPonpesId = $this->getUserPonpesId();

        if (!$this->canAccessAllPonpes() && !$userPonpesId) {
            return redirect()->route('admin.landing-content.index')
                ->with('error', 'Anda belum ditugaskan ke pesantren. Silakan hubungi administrator.');
        }

        $validTypes = ['carousel', 'about_founder', 'about_leader', 'footer', 'section_title'];

        if (!in_array($type, $validTypes)) {
            return redirect()->route('admin.landing-content.create')
                ->with('error', 'Tipe konten tidak valid');
        }

        if ($this->canAccessAllPonpes()) {
            // Admin bisa memilih ponpes mana
            $ponpesList = Ponpes::where('status', 'Aktif')->get();
        } else {
            // User biasa hanya ponpes mereka
            $ponpesList = Ponpes::where('id_ponpes', $userPonpesId)->get();
        }

        return view("admin.landing-content.create-{$type}", compact('ponpesList', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userPonpesId = $this->getUserPonpesId();
        $canAccessAll = $this->canAccessAllPonpes();

        // Validasi untuk user biasa tanpa ponpes_id
        if (!$canAccessAll && !$userPonpesId) {
            return redirect()->route('admin.landing-content.index')
                ->with('error', 'Anda belum ditugaskan ke pesantren. Silakan hubungi administrator.');
        }

        $rules = [
            'content_type' => 'required|in:carousel,about_founder,about_leader,footer,section_title',
            'is_active' => 'nullable|boolean',
        ];

        // Jika admin, bisa memilih ponpes_id
        if ($canAccessAll) {
            $rules['ponpes_id'] = 'required|exists:ponpes,id_ponpes';
        }

        // Tambahkan rules berdasarkan tipe konten
        switch ($request->content_type) {
            case 'carousel':
                $rules['title'] = 'nullable|string|max:255';
                $rules['subtitle'] = 'nullable|string';
                $rules['description'] = 'nullable|string';
                $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $rules['url'] = 'nullable|url|max:255';
                $rules['display_order'] = 'required|integer|min:1';
                break;

            case 'about_founder':
            case 'about_leader':
                $rules['title'] = 'required|string|max:255';
                $rules['position'] = 'required|string|max:100';
                $rules['description'] = 'required|string|min:10';
                $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $rules['url'] = 'nullable|url|max:255';
                $rules['display_order'] = 'nullable|integer|min:0';
                break;

            case 'footer':
                $rules['title'] = 'required|string|max:255';
                $rules['description'] = 'required|string';
                $rules['url'] = 'nullable|url|max:255';
                $rules['display_order'] = 'required|integer|min:1';
                break;

            case 'section_title':
                $rules['title'] = 'required|string|max:255';
                $rules['subtitle'] = 'nullable|string';
                $rules['position'] = 'nullable|string|max:100';
                $rules['display_order'] = 'required|integer|min:1';
                break;
        }

        $validated = $request->validate($rules);

        // Set ponpes_id
        if ($canAccessAll) {
            $validated['ponpes_id'] = $request->ponpes_id;
        } else {
            $validated['ponpes_id'] = $userPonpesId;
        }

        // Handle image upload untuk tipe yang membutuhkan gambar
        if (in_array($request->content_type, ['carousel', 'about_founder', 'about_leader']) && $request->hasFile('image')) {
            $imagePath = $request->file('image')->store('landing-content', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_active'] = $request->has('is_active');

        LandingContent::create($validated);

        return redirect()->route('admin.landing-content.index')
            ->with('success', ucfirst(str_replace('_', ' ', $request->content_type)) . ' berhasil ditambahkan.');
    }

    // ... (method-method lainnya tetap sama)

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LandingContent $landingContent)
    {
        // Check ownership
        if (!$this->checkOwnership($landingContent)) {
            abort(403, 'Unauthorized action.');
        }

        $userPonpesId = $this->getUserPonpesId();
        $ponpesList = Ponpes::where('id_ponpes', $userPonpesId)->get();

        $contentTypes = [
            'carousel' => 'Carousel',
            'about_founder' => 'About Founder',
            'about_leader' => 'About Leader',
            'footer' => 'Footer',
            'section_title' => 'Section Title'
        ];

        return view('admin.landing-content.edit', compact('landingContent', 'ponpesList', 'contentTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LandingContent $landingContent)
    {
        // Check ownership
        if (!$this->checkOwnership($landingContent)) {
            abort(403, 'Unauthorized action.');
        }

        $userPonpesId = $this->getUserPonpesId();

        $rules = [
            'content_type' => 'required|in:carousel,about_founder,about_leader,footer,section_title',
            'is_active' => 'nullable|boolean',
        ];

        // Tambahkan rules berdasarkan tipe konten (sama seperti store)
        switch ($request->content_type) {
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

            case 'footer':
                $rules['title'] = 'required|string|max:255';
                $rules['description'] = 'required|string';
                $rules['url'] = 'nullable|url|max:255';
                $rules['display_order'] = 'required|integer|min:1';
                break;

            case 'section_title':
                $rules['title'] = 'required|string|max:255';
                $rules['subtitle'] = 'nullable|string';
                $rules['position'] = 'nullable|string|max:100';
                $rules['display_order'] = 'required|integer|min:1';
                break;
        }

        $validated = $request->validate($rules);

        // Set ponpes_id dari user yang login (tidak boleh diubah)
        $validated['ponpes_id'] = $userPonpesId;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($landingContent->image && Storage::disk('public')->exists($landingContent->image)) {
                Storage::disk('public')->delete($landingContent->image);
            }

            $imagePath = $request->file('image')->store('landing-content', 'public');
            $validated['image'] = $imagePath;
        } elseif ($request->content_type == 'footer' || $request->content_type == 'section_title') {
            // Hapus image jika tipe tidak memerlukan gambar
            $validated['image'] = null;
        }

        $validated['is_active'] = $request->has('is_active');

        $landingContent->update($validated);

        return redirect()->route('admin.landing-content.index')
            ->with('success', ucfirst(str_replace('_', ' ', $request->content_type)) . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LandingContent $landingContent)
    {
        // Check ownership
        if (!$this->checkOwnership($landingContent)) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image if exists
        if ($landingContent->image && Storage::disk('public')->exists($landingContent->image)) {
            Storage::disk('public')->delete($landingContent->image);
        }

        $landingContent->delete();

        return redirect()->route('admin.landing-content.index')
            ->with('success', 'Konten landing page berhasil dihapus.');
    }

    /**
     * Update display order via AJAX
     */
    public function updateOrder(Request $request)
    {
        $userPonpesId = $this->getUserPonpesId();

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:landing_content,id_content,ponpes_id,' . $userPonpesId,
            'items.*.order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            LandingContent::where('id_content', $item['id'])
                ->where('ponpes_id', $userPonpesId)
                ->update(['display_order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get content detail
     */
    public function getContentDetail($id)
    {
        $userPonpesId = $this->getUserPonpesId();
        $content = LandingContent::with('ponpes')
            ->where('id_content', $id)
            ->where('ponpes_id', $userPonpesId)
            ->firstOrFail();

        return response()->json([
            'id_content' => $content->id_content,
            'title' => $content->title,
            'subtitle' => $content->subtitle,
            'description' => $content->description,
            'position' => $content->position,
            'url' => $content->url,
            'image' => $content->image,
            'image_url' => $content->image ? Storage::url($content->image) : null,
            'content_type' => $content->content_type,
            'is_active' => $content->is_active,
            'display_order' => $content->display_order,
            'created_at_formatted' => $content->created_at->format('d M Y H:i'),
            'updated_at_formatted' => $content->updated_at->format('d M Y H:i'),
            'ponpes' => $content->ponpes ? [
                'id_ponpes' => $content->ponpes->id_ponpes,
                'nama_ponpes' => $content->ponpes->nama_ponpes
            ] : null
        ]);
    }

    public function apiDetail($id)
    {
        return $this->getContentDetail($id);
    }

    /**
     * Toggle status aktivasi
     */
    public function toggleStatus(Request $request, $id)
    {
        $userPonpesId = $this->getUserPonpesId();
        $content = LandingContent::where('id_content', $id)
            ->where('ponpes_id', $userPonpesId)
            ->firstOrFail();

        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $content->update([
            'is_active' => $request->is_active
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Update order single
     */
    public function updateOrderSingle(Request $request, $id)
    {
        $userPonpesId = $this->getUserPonpesId();
        $content = LandingContent::where('id_content', $id)
            ->where('ponpes_id', $userPonpesId)
            ->firstOrFail();

        $request->validate([
            'display_order' => 'required|integer|min:1'
        ]);

        $content->update([
            'display_order' => $request->display_order
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get detail for preview
     */
    public function detail($id)
    {
        $userPonpesId = $this->getUserPonpesId();
        $content = LandingContent::with('ponpes')
            ->where('id_content', $id)
            ->where('ponpes_id', $userPonpesId)
            ->firstOrFail();

        return response()->json([
            'id_content' => $content->id_content,
            'title' => $content->title,
            'subtitle' => $content->subtitle,
            'description' => $content->description,
            'position' => $content->position,
            'url' => $content->url,
            'image' => $content->image,
            'image_url' => $content->image ? Storage::url($content->image) : null,
            'content_type' => $content->content_type,
            'is_active' => $content->is_active,
            'display_order' => $content->display_order,
            'created_at' => $content->created_at->format('d M Y H:i'),
            'created_at_formatted' => $content->created_at->format('d M Y H:i'),
            'updated_at_formatted' => $content->updated_at->format('d M Y H:i'),
            'ponpes' => $content->ponpes ? [
                'id_ponpes' => $content->ponpes->id_ponpes,
                'nama_ponpes' => $content->ponpes->nama_ponpes
            ] : null
        ]);
    }

    public function show(LandingContent $landingContent)
    {
        // Check ownership
        if (!$this->checkOwnership($landingContent)) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.landing-content.show', compact('landingContent'));
    }
}
