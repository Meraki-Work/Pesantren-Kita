<?php

namespace App\Http\Controllers;

use App\Models\Ponpes;
use App\Models\LandingContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    /**
     * Method untuk halaman utama landing (landing_utama)
     */
    public function utama()
    {
        return view('landing_utama');
    }

    /**
     * Method untuk halaman about
     */
    public function about()
    {
        return view('landing_about');
    }

    /**
     * Method untuk halaman al-amal
     */
    public function alAmal()
    {
        return view('landing_al-amal');
    }

    /**
     * Method untuk halaman landing page dengan auth (untuk admin)
     */
    public function index(Request $request)
    {
        // Ambil ponpes_id dari parameter atau dari user yang login
        $ponpesId = $request->get('ponpes_id');
        
        // Jika ada ponpes_id di request, gunakan itu
        if ($ponpesId) {
            $ponpes = Ponpes::findOrFail($ponpesId);
        } 
        // Jika user login dan punya ponpes_id, gunakan itu (untuk admin panel)
        elseif (auth()->check() && auth()->user()->ponpes_id) {
            $ponpes = Ponpes::findOrFail(auth()->user()->ponpes_id);
        }
        // Jika tidak ada parameter dan user tidak login, redirect ke home
        else {
            return redirect()->route('landing_utama');
        }
        
        // Ambil semua konten landing page untuk ponpes ini yang aktif
        $contents = LandingContent::where('ponpes_id', $ponpes->id_ponpes)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();
        
        // Filter berdasarkan tipe konten
        $carousels = $contents->where('content_type', 'carousel');
        $founders = $contents->where('content_type', 'about_founder');
        $leaders = $contents->where('content_type', 'about_leader');
        $visions = $contents->where('content_type', 'about_vision');
        $missions = $contents->where('content_type', 'about_mision');
        $footerLinks = $contents->where('content_type', 'footer');
        
        // Ambil URL logo ponpes
        $logoUrl = $this->getLogoUrl($ponpes);
        
        return view('landing.index', compact(
            'ponpes', 
            'carousels',
            'founders',
            'leaders',
            'visions',
            'missions',
            'footerLinks',
            'contents',
            'logoUrl'
        ));
    }

   
   
    /**
     * Show landing page for specific ponpes (Public Access - Universal)
     */
    public function showPublic(Request $request, $ponpesIdentifier)
    {
        // Cari pesantren berdasarkan identifier (bisa ID atau slug)
        $ponpes = $this->findPonpes($ponpesIdentifier);
        
        if (!$ponpes) {
            abort(404, 'Pondok Pesantren tidak ditemukan');
        }
        
        // Ambil semua konten landing page untuk ponpes ini yang aktif
        $contents = LandingContent::where('ponpes_id', $ponpes->id_ponpes)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();
        
        // Filter berdasarkan tipe konten
        $carousels = $contents->where('content_type', 'carousel');
        $founders = $contents->where('content_type', 'about_founder');
        $leaders = $contents->where('content_type', 'about_leader');
        $visions = $contents->where('content_type', 'about_vision');
        $missions = $contents->where('content_type', 'about_mision');
        $footerLinks = $contents->where('content_type', 'footer');
        
        // Ambil URL logo ponpes
        $logoUrl = $this->getLogoUrl($ponpes);
        
        return view('landing.index', compact(
            'ponpes', 
            'carousels',
            'founders',
            'leaders',
            'visions',
            'missions',
            'footerLinks',
            'contents',
            'logoUrl'
        ));
    }
    
    /**
     * Helper function untuk mendapatkan URL logo ponpes
     */
    private function getLogoUrl($ponpes)
    {
        if (!$ponpes->logo_ponpes) {
            return null;
        }
        
        $logoPath = $ponpes->logo_ponpes;
        
        // Jika sudah berupa URL lengkap, gunakan langsung
        if (Str::startsWith($logoPath, ['http://', 'https://'])) {
            return $logoPath;
        } 
        // Jika berupa path storage, generate URL
        elseif (Storage::exists($logoPath)) {
            return Storage::url($logoPath);
        }
        // Jika hanya nama file, coba cari di public storage
        else {
            // Coba beberapa kemungkinan path
            $possiblePaths = [
                'public/' . $logoPath,
                'public/uploads/ponpes/' . $logoPath,
                'public/images/ponpes/' . $logoPath,
                'storage/app/public/' . $logoPath,
                $logoPath
            ];
            
            foreach ($possiblePaths as $path) {
                if (Storage::exists($path)) {
                    return Storage::url($path);
                }
            }
            
            return null;
        }
    }
    
    /**
     * Method untuk route /landing (universal)
     */
    public function indexUniversal(Request $request)
    {
        // Jika user login dan punya ponpes_id
        if (auth()->check() && auth()->user()->ponpes_id) {
            $ponpes = Ponpes::find(auth()->user()->ponpes_id);
            if ($ponpes) {
                return $this->showPublic($request, $ponpes->id_ponpes);
            }
        }
        
        // Jika ada ponpes_id di query parameter
        $ponpesId = $request->get('ponpes_id');
        if ($ponpesId) {
            return $this->showPublic($request, $ponpesId);
        }
        
        // Jika ada nama ponpes di query parameter
        $ponpesNama = $request->get('nama_ponpes');
        if ($ponpesNama) {
            return $this->showPublic($request, $ponpesNama);
        }
        
        // Default: tampilkan list ponpes
        return $this->listPublic();
    }
    
    /**
     * Helper untuk mencari ponpes berdasarkan identifier
     */
    private function findPonpes($identifier)
    {
        // Coba cari berdasarkan ID
        $ponpes = Ponpes::where('id_ponpes', $identifier)
            ->where('status', 'Aktif')
            ->first();
        
        // Jika tidak ditemukan, coba berdasarkan nama
        if (!$ponpes) {
            // Jika identifier mengandung tanda hubung, kemungkinan slug
            if (str_contains($identifier, '-')) {
                $ponpes = Ponpes::where('nama_ponpes', 'like', '%' . str_replace('-', ' ', $identifier) . '%')
                    ->where('status', 'Aktif')
                    ->first();
            } else {
                // Coba cari berdasarkan nama ponpes
                $ponpes = Ponpes::where('nama_ponpes', 'like', "%{$identifier}%")
                    ->where('status', 'Aktif')
                    ->first();
            }
        }
        
        return $ponpes;
    }
    
    /**
     * Show landing page by ID (Public Access)
     */
    public function showById($id)
    {
        return $this->showPublic(request(), $id);
    }
    
    /**
     * Show landing page by slug (Public Access)
     */
    public function showBySlug($slug)
    {
        return $this->showPublic(request(), $slug);
    }
    
    /**
     * List all ponpes for public
     */
    public function listPublic()
    {
        $ponpesList = Ponpes::where('status', 'Aktif')
            ->where(function($query) {
                $query->whereHas('landingContents', function($q) {
                    $q->where('is_active', true);
                })
                ->orWhereNotNull('logo_ponpes');
            })
            ->withCount(['landingContents' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('nama_ponpes')
            ->paginate(12);
        
        // Tambahkan URL logo untuk setiap ponpes
        $ponpesList->each(function($ponpes) {
            $ponpes->logo_url = $this->getLogoUrl($ponpes);
        });
        
        return view('landing.public-list', compact('ponpesList'));
    }
    
    /**
     * Preview untuk admin (dengan auth)
     */
    public function preview()
    {
        if (!auth()->check() || !auth()->user()->ponpes_id) {
            abort(403, 'Anda tidak memiliki akses ke landing page');
        }
        
        $ponpes = Ponpes::find(auth()->user()->ponpes_id);
        if (!$ponpes) {
            abort(404, 'Pondok Pesantren tidak ditemukan');
        }
        
        return $this->showPublic(request(), $ponpes->id_ponpes);
    }
}