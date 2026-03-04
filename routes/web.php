<?php

use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\FacilityController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LecturerController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Middleware\SetLocaleFromRoute;
use App\Http\Middleware\SetTenantByDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware([SetTenantByDomain::class])
    ->get('/', function (Request $request) {
        $locale = session('locale') ?? config('app.locale');

        if (! in_array($locale, ['en', 'id'], true)) {
            $locale = config('app.locale');
        }

        return redirect()->to("/{$locale}");
    })
    ->name('public.locale.redirect');

Route::group([
    'middleware' => [SetTenantByDomain::class, SetLocaleFromRoute::class],
    'prefix' => '{locale}',
    'where' => ['locale' => 'en|id'],
], function (): void {
    Route::get('/', HomeController::class)
        ->name('public.home');

    // News Routes
    Route::get('/news', [NewsController::class, 'index'])
        ->name('public.news.index');

    Route::get('/news/{slug}', [NewsController::class, 'show'])
        ->name('public.news.show');

    // Indonesian aliases for news
    Route::get('/berita', [NewsController::class, 'index'])
        ->name('public.news.index.id');

    Route::get('/berita/{slug}', [NewsController::class, 'show'])
        ->name('public.news.show.id');

    // Lecturers Routes
    Route::get('/lecturers', [LecturerController::class, 'index'])
        ->name('public.lecturers.index');

    Route::get('/dosen', [LecturerController::class, 'index'])
        ->name('public.lecturers.index.id');

    // Facilities Routes
    Route::get('/facilities', [FacilityController::class, 'index'])
        ->name('public.facilities.index');

    Route::get('/facilities/{slug}', [FacilityController::class, 'show'])
        ->name('public.facilities.show');

    Route::get('/fasilitas', [FacilityController::class, 'index'])
        ->name('public.facilities.index.id');

    Route::get('/fasilitas/{slug}', [FacilityController::class, 'show'])
        ->name('public.facilities.show.id');

    // About Routes
    Route::get('/about', AboutController::class)
        ->name('public.about');

    Route::get('/tentang-kami', AboutController::class)
        ->name('public.about.id');

    // Contact Routes
    Route::get('/contact', [ContactController::class, 'index'])
        ->name('public.contact');

    Route::post('/contact', [ContactController::class, 'store'])
        ->name('public.contact.store');

    Route::get('/kontak-kami', [ContactController::class, 'index'])
        ->name('public.contact.id');

    Route::post('/kontak-kami', [ContactController::class, 'store'])
        ->name('public.contact.store.id');
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
