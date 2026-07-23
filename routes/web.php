<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
use App\Http\Controllers\Auth\GoogleController;

// Rute buat nembak ke Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');

// Rute balikan dari Google (Callback)
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/kas-kelas', [\App\Http\Controllers\GSheetController::class, 'indexKasKelas']);
Route::get('/pribadi', [\App\Http\Controllers\GSheetController::class, 'indexKasPribadi']);
Route::get('/kas-kelas/download-pdf', [\App\Http\Controllers\GSheetController::class, 'downloadPDF'])->name('kas.pdf');
Route::get('/pribadi/download-pdf', [\App\Http\Controllers\GSheetController::class, 'downloadPDFPribadi'])->name('pribadi.pdf');


// --- RUTE AKSI KAS KELAS ---
Route::post('/simpan-kas-kelas', [\App\Http\Controllers\GSheetController::class, 'simpanKasKelas']);
Route::post('/update-kas-kelas/{id}', [\App\Http\Controllers\GSheetController::class, 'updateKasKelas']);
Route::delete('/hapus-kas-kelas/{id}', [\App\Http\Controllers\GSheetController::class, 'hapusKasKelas']);

// --- RUTE AKSI KAS PRIBADI ---
Route::post('/simpan-kas-pribadi', [\App\Http\Controllers\GSheetController::class, 'simpanKasPribadi']);
Route::post('/update-kas-pribadi/{id}', [\App\Http\Controllers\GSheetController::class, 'updateKasPribadi']);
Route::delete('/hapus-kas-pribadi/{id}', [\App\Http\Controllers\GSheetController::class, 'hapusKasPribadi']);

Route::get('/siswa', [App\Http\Controllers\GSheetController::class, 'indexSiswa'])->name('siswa.index');
Route::post('/siswa', [App\Http\Controllers\GSheetController::class, 'simpanSiswa'])->name('siswa.simpan');