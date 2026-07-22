<?php

use Illuminate\Support\Facades\route;
use App\Http\Controllers\GsheetControllers;

Route::get('/', function () {
    return redirect('/kas-kelas');
});