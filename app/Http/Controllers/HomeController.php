<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = News::published()
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $galleryImages = Gallery::active()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        return view('pages.home', compact('latestNews', 'galleryImages'));
    }
}
