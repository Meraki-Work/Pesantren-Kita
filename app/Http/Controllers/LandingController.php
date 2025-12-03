<?php

namespace App\Http\Controllers;

use App\Models\Ponpes;
use App\Models\LandingContent;
use App\Models\Gambar;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page untuk pesantren tertentu
     */
    public function index($ponpesId = null)
    {
        // Jika tidak ada ponpesId, ambil ponpes pertama atau default
        if (!$ponpesId) {
            $ponpes = Ponpes::where('status', 'Aktif')->first();
            if (!$ponpes) {
                abort(404, 'Tidak ada pesantren aktif');
            }
            $ponpesId = $ponpes->id_ponpes;
        } else {
            $ponpes = Ponpes::where('id_ponpes', $ponpesId)
                ->where('status', 'Aktif')
                ->firstOrFail();
        }

        // Ambil semua konten landing page
        $carousels = LandingContent::active()
            ->byPonpes($ponpesId)
            ->type('carousel')
            ->orderBy('display_order')
            ->get();

        $founder = LandingContent::active()
            ->byPonpes($ponpesId)
            ->type('about_founder')
            ->first();

        $leader = LandingContent::active()
            ->byPonpes($ponpesId)
            ->type('about_leader')
            ->first();

        $footerData = LandingContent::active()
            ->byPonpes($ponpesId)
            ->type('footer')
            ->orderBy('display_order')
            ->get();

        $sectionTitles = LandingContent::active()
            ->byPonpes($ponpesId)
            ->type('section_title')
            ->orderBy('display_order')
            ->get();

        // Ambil gambar untuk galeri
        $gallery = Gambar::landingGallery()
            ->byPonpes($ponpesId)
            ->orderBy('display_order')
            ->get();

        return view('landing.index', compact(
            'ponpes',
            'carousels',
            'founder',
            'leader',
            'footerData',
            'sectionTitles',
            'gallery'
        ));
    }

    /**
     * API untuk mendapatkan data landing page (JSON)
     */
    public function apiLanding($ponpesId)
    {
        $ponpes = Ponpes::where('id_ponpes', $ponpesId)
            ->where('status', 'Aktif')
            ->firstOrFail();

        $data = [
            'ponpes' => $ponpes,
            'carousels' => LandingContent::active()
                ->byPonpes($ponpesId)
                ->type('carousel')
                ->orderBy('display_order')
                ->get(),
            'about' => [
                'founder' => LandingContent::active()
                    ->byPonpes($ponpesId)
                    ->type('about_founder')
                    ->first(),
                'leader' => LandingContent::active()
                    ->byPonpes($ponpesId)
                    ->type('about_leader')
                    ->first(),
            ],
            'footer' => LandingContent::active()
                ->byPonpes($ponpesId)
                ->type('footer')
                ->orderBy('display_order')
                ->get(),
            'gallery' => Gambar::landingGallery()
                ->byPonpes($ponpesId)
                ->orderBy('display_order')
                ->get(),
        ];

        return response()->json($data);
    }
}