<?php

use Illuminate\Support\Facades\route;
use App\Http\Controllers\GsheetControllers;

Route::get('/', function () {
    return redirect('/kas-kelas');
});

Route::get('/kas-kelas', [GsheetController::class, 'indexKasKelas']);
Route::post('/simpan-kas-kelas', [GsheetController::class, 'simpanKasKelas']);

