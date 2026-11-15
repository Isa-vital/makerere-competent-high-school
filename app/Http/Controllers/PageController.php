<?php

namespace App\Http\Controllers;

use App\Models\AcademicCombination;
use App\Models\AcademicSubject;
use App\Models\Achievement;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\HistoryMilestone;
use App\Models\Leadership;
use App\Models\News;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        // CHANGED: pull leadership/facilities/achievements/history from DB
        $leadership   = Leadership::active()->category('school_leadership')->orderBy('sort_order')->get();
        $facilities   = Facility::active()->orderBy('sort_order')->get();
        $achievements = Achievement::active()->category('school')->orderBy('sort_order')->get();
        $milestones   = HistoryMilestone::active()->orderBy('sort_order')->get();

        return view('pages.about', compact('leadership', 'facilities', 'achievements', 'milestones'));
    }

    public function academics()
    {
        $academicNews = News::published()
            ->where('category', 'academic')
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        // CHANGED: pull subjects/combinations/academic-results/facilities from DB
        $oLevelSubjects  = AcademicSubject::active()->level('O-Level')->orderBy('sort_order')->get();
        $combinations    = AcademicCombination::active()->orderBy('sort_order')->get()->groupBy('faculty');
        $academicResults = Achievement::active()->category('academic_results')->orderBy('sort_order')->get();
        $facilities      = Facility::active()->orderBy('sort_order')->get();

        return view('pages.academics', compact(
            'academicNews', 'oLevelSubjects', 'combinations', 'academicResults', 'facilities'
        ));
    }

    public function circular()
    {
        return view('pages.circular');
    }

    public function gallery(Request $request)
    {
        $category = $request->query('category');
        $availableImages = Gallery::active()
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get()
            ->filter(fn ($image) => school_image_url($image->image_path) !== null);

        $galleryImages = $category ? $availableImages->where('category', $category) : $availableImages;
        $categories = $availableImages->pluck('category')->filter()->unique()->sort()->values();
        $categoryCounts = $availableImages->countBy('category');

        return view('pages.gallery', compact('galleryImages', 'categories', 'categoryCounts', 'category'));
    }
}
