<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelaporanController;
use App\Http\Controllers\AuthController;
use Illuminate\View\View;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\SubkegiatanController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/api/kegiatan/{pptk}', [ApiController::class, 'getKegiatan']);
Route::get('/api/subkegiatan/{kegiatanId}', [ApiController::class, 'getSubKegiatan']);
Route::get('/api/subkegiatan/detail/{subkegiatanId}', [ApiController::class, 'getSubKegiatanDetail']);


// Halaman login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Grup route yang butuh login
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function (): View {
        return view('layouts.dashboard');
    })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');


    // Halaman pelaporan (pakai controller)
    Route::get('/pelaporan/daftar', [PelaporanController::class, 'index'])->name('pelaporan.daftar');
    Route::get('/pelaporan/masuk', [PelaporanController::class, 'masuk'])->name('pelaporan.masuk');
    Route::delete('/laporan/{id}', [PelaporanController::class, 'destroy'])->name('laporan.destroy');



    // Submit form pelaporan
    Route::post('/pelaporan/store', [PelaporanController::class, 'store'])->name('pelaporan.store');
    Route::post('/pelaporan/update/{id}', [PelaporanController::class, 'update'])->name('pelaporan.update');


    Route::post('/pelaporan/masuk', [PelaporanController::class, 'store']);
    Route::post('/laporan/verifikasi', action: [LaporanController::class, 'verifikasi'])->name('laporan.verifikasi');
    Route::post('/laporan/revisi', [LaporanController::class, 'revisi'])->name('laporan.revisi');
    Route::post('/laporan/verifikasi-revisi', [LaporanController::class, 'handleVerifikasiRevisi']);

    Route::get('/kelola/kegiatan', [KegiatanController::class, 'index'])->name('kelola.kegiatan');
    Route::resource('kegiatan', App\Http\Controllers\KegiatanController::class);

    Route::get('/kelola/subkegiatan', [SubkegiatanController::class, 'index'])->name('kelola.subkegiatan');
    Route::resource('subkegiatan', SubkegiatanController::class);
    Route::post('/subkegiatan/update/{id}', [SubkegiatanController::class, 'update']);

    Route::get('/kelola/akun', [AkunController::class, 'index'])->name('kelola.akun');
    Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
    Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');
    Route::delete('/akun/{id}', [AkunController::class, 'destroy'])->name('akun.destroy');

});
