<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

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
