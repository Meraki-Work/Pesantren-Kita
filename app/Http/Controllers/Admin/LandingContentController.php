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
     * Check ownership of landing content
     */
    private function checkOwnership($landingContent)
    {
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

        $validTypes = [
            'carousel',
            'about_founder',
            'about_leader',
            'about_vision',
            'about_mision',
            'program_list',
            'gallery',
            'testimony',
            'cta',
            'footer'
        ];

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
            'content_type' => 'required|in:carousel,about_founder,about_leader,about_vision,about_mision,program_list,gallery,testimony,cta,footer',
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

            case 'about_vision':
            case 'about_mision':
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
                $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
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

            case 'footer':
                $rules['title'] = 'required|string|max:255';
                $rules['description'] = 'required|string';
                $rules['url'] = 'nullable|url|max:255';
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
        $imageTypes = ['carousel', 'about_founder', 'about_leader', 'gallery', 'testimony', 'cta', 'about_vision', 'about_mision', 'program_list'];

        if (in_array($request->content_type, $imageTypes) && $request->hasFile('image')) {
            $imagePath = $request->file('image')->store('landing-content', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_active'] = $request->has('is_active');

        LandingContent::create($validated);

        $contentTypeNames = [
            'carousel' => 'Carousel',
            'about_founder' => 'Founder/Pendiri',
            'about_leader' => 'Leader/Pengurus',
            'about_vision' => 'Visi',
            'about_mision' => 'Misi',
            'program_list' => 'Program',
            'gallery' => 'Galeri',
            'testimony' => 'Testimoni',
            'cta' => 'Call to Action',
            'footer' => 'Footer Link'
        ];

        return redirect()->route('admin.landing-content.index')
            ->with('success', $contentTypeNames[$request->content_type] . ' berhasil ditambahkan.');
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
            'content_type' => 'required|in:carousel,about_founder,about_leader,about_vision,about_mision,program_list,gallery,testimony,cta,footer',
            'is_active' => 'nullable|boolean',
        ];

        // Tambahkan rules berdasarkan tipe konten
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

            case 'about_vision':
            case 'about_mision':
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

            case 'footer':
                $rules['title'] = 'required|string|max:255';
                $rules['description'] = 'required|string';
                $rules['url'] = 'nullable|url|max:255';
                $rules['display_order'] = 'required|integer|min:1';
                break;
        }

        $validated = $request->validate($rules);

        // Set ponpes_id dari user yang login (tidak boleh diubah)
        $validated['ponpes_id'] = $userPonpesId;

        // Handle image upload
        $imageTypes = ['carousel', 'about_founder', 'about_leader', 'gallery', 'testimony', 'cta', 'about_vision', 'about_mision', 'program_list'];

        if (in_array($request->content_type, $imageTypes) && $request->hasFile('image')) {
            // Delete old image if exists
            if ($landingContent->image && Storage::disk('public')->exists($landingContent->image)) {
                Storage::disk('public')->delete($landingContent->image);
            }

            $imagePath = $request->file('image')->store('landing-content', 'public');
            $validated['image'] = $imagePath;
        } elseif ($request->content_type == 'footer') {
            // Hapus image jika tipe footer
            $validated['image'] = null;
        }

        $validated['is_active'] = $request->has('is_active');

        $landingContent->update($validated);

        $contentTypeNames = [
            'carousel' => 'Carousel',
            'about_founder' => 'Founder/Pendiri',
            'about_leader' => 'Leader/Pengurus',
            'about_vision' => 'Visi',
            'about_mision' => 'Misi',
            'program_list' => 'Program',
            'gallery' => 'Galeri',
            'testimony' => 'Testimoni',
            'cta' => 'Call to Action',
            'footer' => 'Footer Link'
        ];

        return redirect()->route('admin.landing-content.index')
            ->with('success', $contentTypeNames[$request->content_type] . ' berhasil diperbarui.');
    }

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
            'total' => $contents->total(),
            'active' => $statsQuery->clone()->where('is_active', true)->count(),
            'with_images' => $statsQuery->clone()->whereNotNull('image')->count(),
            'carousel_count' => $statsQuery->clone()->where('content_type', 'carousel')->count(),
            'founder_count' => $statsQuery->clone()->where('content_type', 'about_founder')->count(),
            'leader_count' => $statsQuery->clone()->where('content_type', 'about_leader')->count(),
        ];

        return view('admin.landing-content.index', compact('contents', 'ponpesList', 'stats', 'userPonpes'));
    }
}
