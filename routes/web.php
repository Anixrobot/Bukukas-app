<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\GSheetController;

Route::get('/tes-sheet', [GSheetController::class, 'testKoneksi']);
