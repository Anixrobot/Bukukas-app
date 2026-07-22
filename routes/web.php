<?php

use Illuminate\Support\Facades\route;
use App\Http\Controllers\GsheetControllers;

Route::get('/', function () {
    return redirect('/kas-kelas');
});

Route::get('/kas-kelas', [GsheetController::class, 'indexKasKelas']);
Route::post('/simpan-kas-kelas', [GsheetController::class, 'simpanKasKelas']);

Route::get('/pribadi', [GSheetController::class, 'indexKasPribadi']);
Route::post('/simpan-kas-pribadi', [GSheetController::class, 'simpanKasPribadi']);