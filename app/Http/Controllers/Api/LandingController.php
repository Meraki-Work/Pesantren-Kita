<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LandingCarousel;
use App\Models\LandingAbouts;
use App\Models\LandingGalleries;
use App\Models\LandingFooters;

class LandingController extends Controller
{
    // ===================== CAROUSEL =====================
    public function getCarousel()
    {
        return response()->json([
            'data' => LandingCarousel::latest()->get()
        ]);
    }

    public function storeCarousel(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
        ]);

        $file = $request->file('image');
        $nama = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/carousel'), $nama);

        LandingCarousel::create([
            'image' => $nama,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
        ]);

        return response()->json(['message' => 'Carousel berhasil disimpan']);
    }

    // ===================== ABOUTS (Founder & Kepala Yayasan) =====================
    public function getAbout()
    {
        return response()->json([
            'data' => LandingAbouts::first()
        ]);
    }

    public function storeAbout(Request $request)
    {
        $abouts = LandingAbouts::first() ?? new LandingAbouts();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imgName = time().'_about.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/about'), $imgName);
            $abouts->image = $imgName;
        }

        $abouts->title = $request->title;
        $abouts->subtitle = $request->subtitle;
        $abouts->description = $request->description;
        $abouts->save();

        return response()->json(['message' => 'About berhasil diperbarui']);
    }

    public function updateAbout(Request $request)
    {
        $about = LandingAbouts::first();
        if (!$about) {
            $about = new LandingAbouts();
        }

        // Founder
        $about->founder_name = $request->founder_name;
        $about->founder_position = $request->founder_position;
        $about->founder_description = $request->founder_description;

        // Leader
        $about->leader_name = $request->leader_name;
        $about->leader_position = $request->leader_position;
        $about->leader_description = $request->leader_description;

        // Founder Image
        if ($request->hasFile('founder_image')) {
            $file = $request->file('founder_image');
            $filename = time() . '_founder.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/about'), $filename);
            $about->founder_image = $filename;
        }

        // Leader Image
        if ($request->hasFile('leader_image')) {
            $file = $request->file('leader_image');
            $filename = time() . '_leader.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/about'), $filename);
            $about->leader_image = $filename;
        }

        $about->save();

        return response()->json(['message' => 'About berhasil diperbarui']);
    }

    // ===================== GALLERY =====================
    public function getGallery()
    {
        return response()->json([
            'data' => LandingGalleries::latest()->get()
        ]);
    }

    public function storeGallery(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
        ]);

        $file = $request->file('image');
        $nama = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/gallery'), $nama);

        LandingGalleries::create([
            'image' => $nama,
        ]);

        return response()->json(['message' => 'Gallery berhasil disimpan']);
    }

    // ===================== FOOTER =====================
    public function storeFooter(Request $request)
    {
        $footer = LandingFooters::first() ?? new LandingFooters();

        // Upload Logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoName = time() . '_logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/footer'), $logoName);
            $footer->logo = $logoName;
        }

        $footer->instagram = $request->instagram;
        $footer->whatsapp = $request->whatsapp;
        $footer->alamat = $request->alamat;
        $footer->copyright = $request->copyright;
        $footer->save();

        return response()->json([
            'message' => 'Footer berhasil diperbarui'
        ]);
    }
}
