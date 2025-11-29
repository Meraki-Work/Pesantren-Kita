<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ponpes;

class LandingController extends Controller
{
    public function index()
    {
        $ponpes = Ponpes::first();

        return view('admin.landing.index', [
            'carousels' => $ponpes->carousels()->latest()->take(3)->get(),
            'abouts'    => $ponpes->abouts()->first(),
            'galleries' => $ponpes->galleries()->latest()->take(8)->get(),
            'footers'   => $ponpes->footers()->first(),
        ]);
    }
}

