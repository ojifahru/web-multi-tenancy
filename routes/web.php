<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\LecturerController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\FacilityController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Middleware\SetTenantByDomain;
use Illuminate\Support\Facades\Route;

Route::middleware(SetTenantByDomain::class)->group(function (): void {
    Route::get('/', HomeController::class)
        ->name('public.home');

    Route::get('/berita', [NewsController::class, 'index'])
        ->name('public.news.index');

    Route::get('/berita/{slug}', [NewsController::class, 'show'])
        ->name('public.news.show');

    Route::get('/dosen', [LecturerController::class, 'index'])
        ->name('public.lecturers.index');

    Route::get('/fasilitas', [FacilityController::class, 'index'])
        ->name('public.facilities.index');

    Route::get('/fasilitas/{slug}', [FacilityController::class, 'show'])
        ->name('public.facilities.show');

    Route::get('/tentang-kami', AboutController::class)
        ->name('public.about');

    Route::get('/kontak-kami', [ContactController::class, 'index'])
        ->name('public.contact');

    Route::post('/kontak-kami', [ContactController::class, 'store'])
        ->name('public.contact.store');
});

/*
|--------------------------------------------------------------------------
| Example slug-based public route structure
|--------------------------------------------------------------------------
|
| Route::get('/{study_program_slug}', [HomeController::class, '__invoke']);
| Route::get('/{study_program_slug}/berita', [NewsController::class, 'index']);
| Route::get('/{study_program_slug}/berita/{news:slug}', [NewsController::class, 'show']);
|
*/
