<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Models\News;
use Illuminate\Support\Facades\Route;

// Preserve links indexed from the former PHP site.
foreach ([
    'index.php' => '/',
    'default.php' => '/',
    'about.php' => '/about',
    'academics.php' => '/academics',
    'admissions.php' => '/admissions',
    'gallery.php' => '/gallery',
    'news.php' => '/news',
    'alumni.php' => '/alumni',
    'contact.php' => '/contact',
    'circular.php' => '/circular',
] as $legacyPath => $newPath) {
    Route::permanentRedirect('/'.$legacyPath, $newPath);
}

Route::get('/sitemap.xml', function () {
    $pages = ['home', 'about', 'academics', 'admissions', 'gallery', 'news.index', 'alumni', 'contact', 'circular'];
    $urls = array_map(fn (string $page): string => route($page), $pages);

    foreach (News::published()->pluck('slug') as $slug) {
        $urls[] = route('news.show', $slug);
    }

    return response()->view('sitemap', compact('urls'))
        ->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about',     [PageController::class, 'about'])->name('about');
Route::get('/academics', [PageController::class, 'academics'])->name('academics');
Route::get('/circular',  [PageController::class, 'circular'])->name('circular');
Route::get('/gallery',   [PageController::class, 'gallery'])->name('gallery');

Route::get('/admissions',  [AdmissionController::class, 'show'])->name('admissions');
Route::post('/admissions', [AdmissionController::class, 'store'])->middleware('throttle:5,1')->name('admissions.store');

Route::get('/alumni',  [AlumniController::class, 'show'])->name('alumni');
Route::post('/alumni', [AlumniController::class, 'store'])->middleware('throttle:5,1')->name('alumni.store');

Route::get('/news',             [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/contact',  [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');
