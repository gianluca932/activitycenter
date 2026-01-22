<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pdf\FoglioServizioController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/pdf/foglio-servizio/{activity}', FoglioServizioController::class)
    ->name('pdf.foglio-servizio')
    ->middleware(['auth']);
