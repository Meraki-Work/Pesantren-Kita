<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingContent;
use App\Models\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Dalam method index()
    public function index(Request $request)
    {
        $ponpesId = $request->query('ponpes_id');
        $contentType = $request->query('content_type');

        $query = LandingContent::with('ponpes');

        if ($ponpesId) {
            $query->where('ponpes_id', $ponpesId);
        }

        if ($contentType) {
            $query->where('content_type', $contentType);
        }

        $contents = $query->orderBy('ponpes_id')
            ->orderBy('content_type')
            ->orderBy('display_order')
            ->paginate(12); // Kurangi items per page untuk card view

        $ponpesList = Ponpes::where('status', 'Aktif')->get();

        // Stats untuk dashboard
        $stats = [
            'total' => $contents->total(),
            'active' => $contents->where('is_active', true)->count(),
            'with_images' => $contents->whereNotNull('image')->count(),
            'carousel_count' => LandingContent::where('content_type', 'carousel')->count(),
            'founder_count' => LandingContent::where('content_type', 'about_founder')->count(),
            'leader_count' => LandingContent::where('content_type', 'about_leader')->count(),
        ];

        return view('admin.landing-content.index-card', compact('contents', 'ponpesList', 'stats'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ponpesList = Ponpes::where('status', 'Aktif')->get();

        return view('admin.landing-content.create', compact('ponpesList'));
    }

    /**
     * Show form berdasarkan tipe konten
     */
    public function createByType(Request $request, $type)
    {
        $validTypes = ['carousel', 'about_founder', 'about_leader', 'footer', 'section_title'];

        if (!in_array($type, $validTypes)) {
            return redirect()->route('admin.landing-content.create')
                ->with('error', 'Tipe konten tidak valid');
        }

        $ponpesList = Ponpes::where('status', 'Aktif')->get();

        return view("admin.landing-content.create-{$type}", compact('ponpesList', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'ponpes_id' => 'required|exists:ponpes,id_ponpes',
            'content_type' => 'required|in:carousel,about_founder,about_leader,footer,section_title',
            'is_active' => 'nullable|boolean',
        ];

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LandingContent $landingContent)
    {
        $ponpesList = Ponpes::where('status', 'Aktif')->get();
        $contentTypes = [
            'carousel' => 'Carousel',
            'about_founder' => 'About Founder',
            'about_leader' => 'About Leader',
            'footer' => 'Footer',
            'section_title' => 'Section Title'
        ];

        // Gunakan view yang sama untuk edit
        return view('admin.landing-content.edit', compact('landingContent', 'ponpesList', 'contentTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LandingContent $landingContent)
    {
        $rules = [
            'ponpes_id' => 'required|exists:ponpes,id_ponpes',
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
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:landing_content,id_content',
            'items.*.order' => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            LandingContent::where('id_content', $item['id'])
                ->update(['display_order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    public function getContentDetail($id)
    {
        $content = LandingContent::with('ponpes')->findOrFail($id);

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

    // Tambahkan method ini ke LandingContentController

/**
 * Toggle status aktivasi
 */
public function toggleStatus(Request $request, $id)
{
    $content = LandingContent::findOrFail($id);
    
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
    $content = LandingContent::findOrFail($id);
    
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
    $content = LandingContent::with('ponpes')->findOrFail($id);
    
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
        return view('admin.landing-content.show', compact('landingContent'));
    }

}
