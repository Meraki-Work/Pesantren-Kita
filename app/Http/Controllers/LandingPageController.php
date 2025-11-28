<?php

namespace App\Http\Controllers;

use App\Models\LandingCarousel;
use App\Models\LandingAbouts;
use App\Models\LandingGalleries;
use App\Models\LandingFooters;

class LandingPageController extends Controller
{
    public function utama()
    {
        $carousels = LandingCarousel::all();
        $abouts    = LandingAbouts::first();
        $galleries = LandingGalleries::all();
        $footer    = LandingFooters::latest()->first();

        return view('landing_utama', compact(
            'carousels',
            'abouts',
            'galleries',
            'footer'
        ));
    }

    public function about()
    {
        return view('landing_about');
    }

    public function alAmal()
    {
        $carousels = LandingCarousel::latest()->take(3)->get();
        $abouts    = LandingAbouts::first();
        $galleries = LandingGalleries::orderBy('id', 'DESC')->take(6)->get();
        $footer    = LandingFooters::latest()->first();

        return view('layouts.landing_al-amal', compact(
            'carousels',
            'abouts',
            'galleries',
            'footer'
        ));
    }
}
