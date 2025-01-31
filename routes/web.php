<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataPegawaiController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\ExportPegawaiController;
use App\Http\Controllers\ImportPegawaiController;
use App\Http\Controllers\ProfilePerusahaanController;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::prefix('/dashboard')->middleware(['auth', 'auto.logout'])->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
});

// Profile Routes
Route::prefix('profile')->group(function () {
    Route::get('/perusahaan', [ProfilePerusahaanController::class, 'index'])->name('profile.index');
    Route::post('/', [ProfilePerusahaanController::class, 'store'])->name('profile.store');
    Route::get('/edit', [ProfilePerusahaanController::class, 'edit'])->name('profile-perusahaan.edit');
    Route::put('/update', [ProfilePerusahaanController::class, 'update'])->name('profile-perusahaan.update');
});

// Pegawai Routes
Route::prefix('pegawai')->name('pegawai.')->group(function () {
    Route::resource('data', DataPegawaiController::class)->parameters(['data' => 'pegawai']);
    Route::get('/export', [ExportPegawaiController::class, 'exportPage'])->name('export.page');
    Route::get('/export/data', [ExportPegawaiController::class, 'export'])->name('export');
});

// Jabatan Routes
Route::prefix('jabatan')->name('jabatan.')->group(function () {
    Route::get('/', [JabatanController::class, 'index'])->name('index');
    Route::post('/', [JabatanController::class, 'store'])->name('store');
    Route::delete('/{jabatan}', [JabatanController::class, 'destroy'])->name('destroy');
});

// Import Routes
Route::prefix('import')->group(function () {
    Route::post('/excel', [ImportPegawaiController::class, 'importExcel'])->name('import.excel');
});

Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
Route::put('/account/update', [AccountController::class, 'update'])->name('account.update');

// User Routes
Route::resource('Pengguna', UserController::class);

//LogActivity Routes
Route::resource('logactivity', LogActivityController::class);




