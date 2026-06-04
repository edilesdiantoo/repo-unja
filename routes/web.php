<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ArticleController as AdminArticle;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\ArticleController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Web\HomeController;

// Halaman utama (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman statis lainnya (opsional, bisa digabung di HomeController)
Route::get('/about', [HomeController::class, 'about'])->name('web.about');
Route::get('/browse', [HomeController::class, 'browse'])->name('web.browse');

Route::get('/panduan', [HomeController::class, 'policy'])->name('web.policy');
// Route untuk detail karya ilmiah
Route::get('/article/{id}', [App\Http\Controllers\Web\HomeController::class, 'show'])->name('web.article.show');
// Route::get('/article/download/{id}', [App\Http\Controllers\Web\HomeController::class, 'download'])->name('web.article.download')->middleware('auth');
Route::get('/article/view/{id}', [HomeController::class, 'viewArticle'])->name('web.article.view');
// auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Group untuk Mahasiswa & Dosen (User)
Route::prefix('user')->name('user.')->group(function () {
    // Nama route: user.dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- SUB MENU 1: UNGGAH KARYA ILMIAH (MULTI-STEP FORM) ---
    // Tahap 1: Upload File Berkas
    Route::get('/upload', [ArticleController::class, 'createStep1'])->name('article.create');
    Route::post('/upload/step1', [ArticleController::class, 'storeStep1'])->name('article.storeStep1');

    // Tahap 2: Isi Metadata (ID didapat setelah Tahap 1 sukses)
    Route::get('/upload/metadata/{id}', [ArticleController::class, 'createStep2'])->name('article.createStep2');
    Route::post('/upload/metadata/{id}', [ArticleController::class, 'storeStep2'])->name('article.storeStep2');

    // --- SUB MENU 2: AJUKAN VERIFIKASI ---
    Route::get('/verifikasi', [ArticleController::class, 'indexVerification'])->name('article.indexVerification');
    Route::post('/verifikasi/ajukan/{id}', [ArticleController::class, 'submitVerification'])->name('article.submitVerification');

    // --- HISTORI & REVISI (FITUR LAMA TETAP AMAN) ---
    Route::get('/history', [ArticleController::class, 'history'])->name('article.history');
    Route::get('/article/{id}/revisi', [ArticleController::class, 'edit'])->name('article.edit');
    Route::put('/article/{id}/update', [ArticleController::class, 'update'])->name('article.update');
    Route::get('/repository/status-verifikasi/preview/{id}', [ArticleController::class, 'previewVerification'])->name('article.previewVerification');

    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [DashboardController::class, 'profileUpdate'])->name('profile.update');

    // Tampilan sub-menu status verifikasi karya ilmiah mahasiswa
    Route::get('/status-verifikasi', [ArticleController::class, 'statusVerifikasi'])->name('article.statusVerifikasi');
    Route::get('/notifications/read/{id}', [\App\Http\Controllers\User\DashboardController::class, 'markAsRead'])->name('notifications.read');
});

// Group Route untuk Admin & Superadmin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/notifications/read/{id}', [AdminDashboard::class, 'markAsRead'])->name('notifications.read');

    // Manajemen Pengguna
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/status', [UserController::class, 'updateStatus'])->name('users.status');

    // Statistik & Profil
    Route::get('/chart-data/{range}', [AdminDashboard::class, 'getChartData'])->name('chart.data');
    Route::get('/profile', [AdminDashboard::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AdminDashboard::class, 'profileUpdate'])->name('profile.update');

    // ===================================================================
    // --- STRUKTUR 3 SUB-MENU REPOSITORI SESUAI ALUR BARU (EDI) ---
    // ===================================================================

    // [Sub-Menu 1] Menunggu Validasi (Status: pending)
    Route::get('/repository/pending', [\App\Http\Controllers\Admin\ArticleController::class, 'pending'])->name('repository.pending');
    Route::get('/repository/verify/{id}', [\App\Http\Controllers\Admin\ArticleController::class, 'verify'])->name('repository.verify');
    Route::patch('/repository/update-status/{id}', [\App\Http\Controllers\Admin\ArticleController::class, 'updateStatus'])->name('repository.update-status');

    // [Sub-Menu 2] Publikasikan Karya Ilmiah (Status: verified)
    Route::get('/repository/publikasi', [\App\Http\Controllers\Admin\ArticleController::class, 'publikasi'])->name('repository.publikasi');
    Route::get('/repository/publikasi/detail/{id}', [\App\Http\Controllers\Admin\ArticleController::class, 'detailPublikasi'])->name('repository.detailPublikasi');
    Route::post('/repository/publikasi/konfirmasi/{id}', [\App\Http\Controllers\Admin\ArticleController::class, 'konfirmasiPublikasi'])->name('repository.konfirmasiPublikasi');

    // [Sub-Menu 3] Data Koleksi (Status: published)
    Route::get('/repository/index', [\App\Http\Controllers\Admin\ArticleController::class, 'index'])->name('repository.index');
    Route::get('/repository/show/{id}', [\App\Http\Controllers\Admin\ArticleController::class, 'show'])->name('repository.show');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // ... route dashboard dan users ...

    // Menu Repositori
    Route::get('/repository/pending', [AdminArticle::class, 'pending'])->name('repository.pending');
    Route::get('/repository/all', [AdminArticle::class, 'index'])->name('repository.index');
    Route::get('/repository/{id}/verify', [AdminArticle::class, 'verify'])->name('repository.verify');
    Route::patch('/repository/{id}/status', [AdminArticle::class, 'updateStatus'])->name('repository.update-status');
    Route::get('/repository', [AdminArticle::class, 'index'])->name('repository.index');
    Route::get('/repository/show/{id}', [AdminArticle::class, 'show'])->name('repository.show');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Menampilkan form filter
    Route::get('/laporan', [ReportController::class, 'index'])->name('report.index');

    // Menampilkan hasil filter (POST)
    Route::post('/laporan/hasil', [ReportController::class, 'generate'])->name('report.generate');

});

// Tambahkan superadmin di sini
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

    // ... route lainnya ...
});

// Halaman input NIM/NIDN
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
// Proses pengecekan identitas
Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('password.email');
Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('password.update');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/notifications/read/{id}', [DashboardController::class, 'markAsRead'])->name('notifications.read');
Route::get('/chart-data/{range}', [DashboardController::class, 'getChartData'])->name('chart.data');
