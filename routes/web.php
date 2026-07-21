<?php

use Illuminate\Support\Facades\Route;

// Bikin halaman awal (root) otomatis langsung diarahkan ke Kas Kelas
Route::get('/', function () {
    return redirect('/kas-kelas');
});

// Jalur untuk membuka Workspace Kas Kelas
Route::get('/kas-kelas', function () {
    return view('dashboard-kelas');
});

// Jalur untuk membuka Workspace Keuangan Pribadi
Route::get('/pribadi', function () {
    return view('dashboard-pribadi');
});