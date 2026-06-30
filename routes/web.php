<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::controller(LandingController::class)->group(function () {
    Route::get('/', 'home')->name('landing');
    Route::get('/sistema-sgd', 'sgd')->name('landing.sgd');
    Route::get('/dicacocu', 'dicacocu')->name('landing.dicacocu');
    Route::get('/contacto', 'contacto')->name('landing.contacto');
});
