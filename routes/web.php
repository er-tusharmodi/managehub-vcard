<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\WebsiteCmsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\WebsiteController;

Route::get('/', [WebsiteController::class, 'show'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])
        ->name('admin.login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
        ->name('admin.login.store');
});

Route::middleware(['admin.auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // Website CMS - Index (Navigation)
    Route::get('/website-cms/{page:slug?}', [WebsiteCmsController::class, 'index'])
        ->name('website-cms');
    
    // Website CMS - Individual Sections
    Route::get('/website-cms/{page:slug}/general', [WebsiteCmsController::class, 'showGeneral'])
        ->name('website-cms.general');
    Route::get('/website-cms/{page:slug}/branding', [WebsiteCmsController::class, 'showBranding'])
        ->name('website-cms.branding');
    Route::get('/website-cms/{page:slug}/social', [WebsiteCmsController::class, 'showSocial'])
        ->name('website-cms.social');
    Route::get('/website-cms/{page:slug}/seo', [WebsiteCmsController::class, 'showSeo'])
        ->name('website-cms.seo');
    Route::get('/website-cms/{page:slug}/hero', [WebsiteCmsController::class, 'showHero'])
        ->name('website-cms.hero');
    Route::get('/website-cms/{page:slug}/categories', [WebsiteCmsController::class, 'showCategories'])
        ->name('website-cms.categories');
    Route::get('/website-cms/{page:slug}/vcard', [WebsiteCmsController::class, 'showVcard'])
        ->name('website-cms.vcard');
    Route::get('/website-cms/{page:slug}/how-it-works', [WebsiteCmsController::class, 'showHowItWorks'])
        ->name('website-cms.how-it-works');
    Route::get('/website-cms/{page:slug}/cta', [WebsiteCmsController::class, 'showCta'])
        ->name('website-cms.cta');
    Route::get('/website-cms/{page:slug}/footer', [WebsiteCmsController::class, 'showFooter'])
        ->name('website-cms.footer');

    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])
        ->name('profile.password');

    Route::resource('admins', AdminUserController::class)->except('show');
});

require __DIR__ . '/auth.php';

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
