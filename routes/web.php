<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GSheetController;

// Halaman Awal
Route::get('/', function () {
    return redirect('/kas-kelas');
});

// Nampilin Halaman Form
Route::get('/kas-kelas', function () {
    return view('dashboard-kelas');
});
Route::get('/pribadi', function () {
    return view('dashboard-pribadi');
});

// JALUR BARU: Buat ngirim data form Kas Kelas ke Controller
Route::post('/simpan-kas-kelas', [GSheetController::class, 'simpanKasKelas']);