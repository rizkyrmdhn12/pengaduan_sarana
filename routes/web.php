<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

// Auth
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/logout',   [AuthController::class, 'logout'])->name('logout');

// Register Siswa
Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/dashboard',              [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/aspirasi',               [AdminController::class, 'listAspirasi'])->name('admin.aspirasi');
    Route::get('/aspirasi/feedback/{id}', [AdminController::class, 'showFeedback'])->name('admin.feedback');
    Route::put('/aspirasi/feedback/{id}', [AdminController::class, 'updateFeedback'])->name('admin.feedback.update');
});

// Siswa
Route::prefix('siswa')->middleware('siswa.auth')->group(function () {
    Route::get('/dashboard',    [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    Route::get('/aspirasi',     [SiswaController::class, 'formAspirasi'])->name('siswa.aspirasi');
    Route::post('/aspirasi',    [SiswaController::class, 'simpanAspirasi'])->name('siswa.aspirasi.store');
    Route::get('/histori',      [SiswaController::class, 'histori'])->name('siswa.histori');
    Route::get('/histori/{id}', [SiswaController::class, 'detailAspirasi'])->name('siswa.detail');
});
