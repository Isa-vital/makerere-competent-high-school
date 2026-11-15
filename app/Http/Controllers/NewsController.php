<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::published()
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('pages.news.index', compact('news'));
    }

    public function show(News $news)
    {
        abort_unless($news->status === 'published', 404);

        $news->increment('views');

        return view('pages.news.show', compact('news'));
    }
}
