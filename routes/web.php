<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientVcardEditorController;
use App\Http\Controllers\Client\VcardSubmissionController as ClientVcardSubmissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\WebsiteCmsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Admin\VcardController as AdminVcardController;
use App\Livewire\Vcards\AdminSectionEditor;
use App\Livewire\Vcards\ClientSectionEditor;
use App\Livewire\Admin\TemplateCodeEditor;
use App\Livewire\Admin\TemplateVisualEditor;
use App\Http\Controllers\VcardPublicController;
use App\Http\Controllers\VcardSubmissionController;

Route::get('/', [WebsiteController::class, 'show'])->name('home');

Route::get('/subscription-inactive', function () {
    return view('subscription-inactive');
})->name('subscription.inactive');

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

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');

    Route::get('/vcards', [AdminVcardController::class, 'index'])->name('vcards.index');
    Route::get('/vcards/create', [AdminVcardController::class, 'create'])->name('vcards.create');
    Route::post('/vcards', [AdminVcardController::class, 'store'])->name('vcards.store');
    Route::get('/vcards/{vcard}/edit', [AdminVcardController::class, 'edit'])->name('vcards.edit');
    Route::put('/vcards/{vcard}', [AdminVcardController::class, 'update'])->name('vcards.update');
    Route::get('/vcards/{vcard}/data/{section?}', AdminSectionEditor::class)->name('vcards.data.section');
    Route::patch('/vcards/{vcard}/status', [AdminVcardController::class, 'updateStatus'])->name('vcards.updateStatus');
    Route::delete('/vcards/{vcard}', [AdminVcardController::class, 'destroy'])->name('vcards.destroy');
    Route::post('/vcards/sync-sections', [AdminVcardController::class, 'syncSections'])->name('vcards.syncSections');
    
    // vCard Share Routes
    Route::get('/vcards/{vcard}/share', [AdminVcardController::class, 'shareVcard'])->name('vcards.share');
    Route::post('/vcards/{vcard}/regenerate-password', [AdminVcardController::class, 'regeneratePassword'])->name('vcards.regeneratePassword');
    Route::post('/vcards/{vcard}/send-credentials', [AdminVcardController::class, 'sendCredentialsToClient'])->name('vcards.sendCredentials');
    
    // Template Management Routes
    Route::get('/templates', [App\Http\Controllers\Admin\TemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/{templateKey}/edit/code', TemplateCodeEditor::class)->name('templates.edit.code');
    Route::get('/templates/{templateKey}/edit/visual/{section?}', TemplateVisualEditor::class)->name('templates.edit.visual');
    Route::post('/templates/{templateKey}/update/code', [App\Http\Controllers\Admin\TemplateController::class, 'updateCode'])->name('templates.update.code');
    Route::post('/templates/{templateKey}/update/visual', [App\Http\Controllers\Admin\TemplateController::class, 'updateVisual'])->name('templates.update.visual');
    Route::delete('/templates/{templateKey}', [App\Http\Controllers\Admin\TemplateController::class, 'destroy'])->name('templates.destroy');
    
    // Template Visibility & Ordering Routes
    Route::post('/templates/{templateKey}/toggle-visibility', [App\Http\Controllers\Admin\TemplateController::class, 'toggleVisibility'])->name('templates.toggleVisibility');
    Route::post('/templates/update-order', [App\Http\Controllers\Admin\TemplateController::class, 'updateOrder'])->name('templates.updateOrder');
    Route::patch('/templates/{templateKey}/display-name', [App\Http\Controllers\Admin\TemplateController::class, 'updateDisplayName'])->name('templates.updateDisplayName');
    Route::patch('/templates/{templateKey}/category', [App\Http\Controllers\Admin\TemplateController::class, 'updateCategory'])->name('templates.updateCategory');
    Route::post('/templates/sync', [App\Http\Controllers\Admin\TemplateController::class, 'syncFilesystem'])->name('templates.sync');
});

// Public Template Routes (no auth required)
Route::get('/template-preview/{templateKey}', [App\Http\Controllers\Admin\TemplateController::class, 'preview'])
    ->name('admin.templates.preview');

Route::get('/template-assets/{templateKey}/{path}', [App\Http\Controllers\Admin\TemplateController::class, 'asset'])
    ->where('path', '.*')
    ->name('templates.asset');

require __DIR__ . '/auth.php';

// Client Dashboard - Authenticated users
Route::middleware(['auth', 'verified', 'subscription.active'])->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    Route::get('/vcards/{vcard}/submissions/{type}', [ClientVcardSubmissionController::class, 'index'])
        ->where('type', 'order|booking|enquiry|contact')
        ->name('client.submissions.index');
    
    Route::patch('/vcards/{vcard}/submissions/{type}/{id}/status', [ClientVcardSubmissionController::class, 'updateStatus'])
        ->where('type', 'order|booking|enquiry|contact')
        ->name('client.submissions.updateStatus');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/vcard/{subdomain}/inactive', [VcardPublicController::class, 'inactive'])
    ->where('subdomain', '[a-z0-9]([a-z0-9-]*[a-z0-9])?')
    ->name('vcard.inactive');

Route::get('/{subdomain}', [VcardPublicController::class, 'show'])
    ->where('subdomain', '[a-z0-9]([a-z0-9-]*[a-z0-9])?')
    ->name('vcard.public.path');

Route::post('/vcard/{subdomain}/submit/{type}', [VcardSubmissionController::class, 'submit'])
    ->where('subdomain', '[a-z0-9]([a-z0-9-]*[a-z0-9])?')
    ->where('type', 'order|booking|enquiry|contact')
    ->middleware('throttle:20,1')
    ->name('vcard.submit.path');

Route::middleware(['auth', 'subscription.active'])->group(function () {
    Route::get('/my-vcard/{subdomain}/edit/{section?}', [ClientVcardEditorController::class, 'edit'])
        ->where('subdomain', '[a-z0-9]([a-z0-9-]*[a-z0-9])?')
        ->name('vcard.editor');
});

Route::domain('{subdomain}.' . config('vcard.base_domain'))->group(function () {
    Route::get('/', [VcardPublicController::class, 'show'])->name('vcard.public');

    Route::get('/inactive', [VcardPublicController::class, 'inactive'])->name('vcard.inactive.domain');

    Route::post('/submit/{type}', [VcardSubmissionController::class, 'submit'])
        ->where('type', 'order|booking|enquiry|contact')
        ->middleware('throttle:20,1')
        ->name('vcard.submit');

    Route::middleware(['auth', 'subscription.active'])->group(function () {
        Route::get('/vcard/edit/{section?}', ClientSectionEditor::class)->name('vcard.editor.section');
    });
});
