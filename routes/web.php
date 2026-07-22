<?php

use Illuminate\Support\Facades\Route; // Benerin 'route' jadi 'Route'
use App\Http\Controllers\GSheetController; // Hapus 's' di akhir, pakai 'S' kapital

Route::get('/', function () {
    return redirect('/kas-kelas');
});

// Samain semua pakai GSheetController (S kapital)
Route::get('/kas-kelas', [GSheetController::class, 'indexKasKelas']);
Route::post('/simpan-kas-kelas', [GSheetController::class, 'simpanKasKelas']);

Route::get('/pribadi', [GSheetController::class, 'indexKasPribadi']);
Route::post('/simpan-kas-pribadi', [GSheetController::class, 'simpanKasPribadi']);

Route::delete('/hapus-kas-pribadi/{id}', [GSheetController::class, 'hapusKasPribadi']);
Route::delete('/hapus-kas-kelas/{id}', [GSheetController::class, 'hapusKasKelas']);