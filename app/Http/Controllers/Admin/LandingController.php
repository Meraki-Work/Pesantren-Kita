<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingCarousel;
use App\Models\LandingAbouts;
use App\Models\LandingGalleries;
use App\Models\LandingFooters;

class LandingController extends Controller
{
    public function index()
    {
        return view('admin.landing.index', [
            'carousels' => LandingCarousel::latest()->take(3)->get(),   
            'abouts'    => LandingAbouts::first(),                     
            'galleries' => LandingGalleries::latest()->take(8)->get(), 
            'footers'   => LandingFooters::first(),                    
        ]);
    }
}

