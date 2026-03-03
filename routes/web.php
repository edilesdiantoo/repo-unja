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
Route::get('/article/download/{id}', [App\Http\Controllers\Web\HomeController::class, 'download'])->name('web.article.download')->middleware('auth');

// auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Group untuk Mahasiswa & Dosen (User)
Route::prefix('user')->name('user.')->group(function () {
    // Nama route: user.dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Nama route: user.article.create
    Route::get('/upload', [ArticleController::class, 'create'])->name('article.create');
    Route::post('/upload', [ArticleController::class, 'store'])->name('article.store');
    Route::get('/history', [ArticleController::class, 'history'])->name('article.history');
    Route::get('/article/{id}/revisi', [ArticleController::class, 'edit'])->name('article.edit');
    Route::put('/article/{id}/update', [ArticleController::class, 'update'])->name('article.update');

    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [DashboardController::class, 'profileUpdate'])->name('profile.update');
});

// Group Route untuk Admin & Superadmin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::get('/notifications/read/{id}', [AdminDashboard::class, 'markAsRead'])->name('notifications.read');
    // Di dalam Route::prefix('admin')->name('admin.')->group(function () { ... })

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{id}/status', [UserController::class, 'updateStatus'])->name('users.status');

    Route::get('/chart-data/{range}', [AdminDashboard::class, 'getChartData'])->name('chart.data');

    Route::get('/profile', [AdminDashboard::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AdminDashboard::class, 'profileUpdate'])->name('profile.update');
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
Route::get('/notifications/read/{id}', [App\Http\Controllers\User\DashboardController::class, 'markAsRead'])->name('user.notifications.read');
