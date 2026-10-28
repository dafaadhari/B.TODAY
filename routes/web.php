<?php

use App\Http\Controllers\Admin\AdvertisementController as AdminAdController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuestSubmissionController as AdminSubmissionController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GuestSubmissionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StaticPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Portal Routes
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/berita/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Public Guest Submission (Form tanpa akun + throttle 5/min)
Route::get('/kirim-tulisan', [GuestSubmissionController::class, 'create'])->name('kirim-tulisan.create');
Route::post('/kirim-tulisan', [GuestSubmissionController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('kirim-tulisan.store');

Route::get('/cari', SearchController::class)->name('search');
Route::get('/pasang-iklan', [StaticPageController::class, 'pasangIklan'])->name('pasang-iklan');
Route::get('/redaksi', [StaticPageController::class, 'redaksi'])->name('redaksi');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:6,1')->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin CMS Routes (Protected by Auth)
|--------------------------------------------------------------------------
*/
// auth.session: sesi lain ikut keluar ketika password akun diganti.
Route::prefix('admin')->middleware(['auth', 'auth.session'])->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    // Articles Management & Approval Workflow
    Route::resource('articles', AdminArticleController::class)->except(['show']);
    Route::post('articles/{article}/approve', [AdminArticleController::class, 'approve'])->name('articles.approve');

    // Guest Submissions Review Queue
    Route::get('submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::post('submissions/{submission}/approve', [AdminSubmissionController::class, 'approve'])->name('submissions.approve');
    Route::post('submissions/{submission}/reject', [AdminSubmissionController::class, 'reject'])->name('submissions.reject');

    // Advertisements Management
    Route::resource('ads', AdminAdController::class)->only(['index', 'store', 'destroy']);
    Route::post('ads/{ad}/toggle', [AdminAdController::class, 'toggleActive'])->name('ads.toggle');

    // Categories Management
    Route::resource('categories', AdminCategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    // Profil akun yang sedang login
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->middleware('throttle:6,1')->name('profile.password');
});
