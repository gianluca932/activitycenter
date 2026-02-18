<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pdf\FoglioServizioController;



Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/pdf/foglio-servizio/{activity}', FoglioServizioController::class)
    ->name('pdf.foglio-servizio')
    ->middleware(['auth']);
