<?php

namespace App\Http\Controllers;

use App\Models\LandingCarousel;
use App\Models\LandingAbouts;
use App\Models\LandingGalleries;
use App\Models\LandingFooters;
use App\Models\Ponpes;

class LandingPageController extends Controller
{
    public function utama()
    {
        return view('landing_utama');
    }

    public function about()
    {
        return view('landing_about');
    }

    public function alAmal()
    {
        $ponpes = Ponpes::first();

        return view('layouts.landing_al-amal', [
            'carousels' => $ponpes->carousels()->latest()->take(3)->get(),
            'abouts'    => $ponpes->abouts()->first(),
            'galleries' => $ponpes->galleries()->latest()->take(6)->get(),
            'footer'    => $ponpes->footers()->first(),
        ]);
    }
}
