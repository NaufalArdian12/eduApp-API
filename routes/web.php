<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/docs', function () {
    return redirect('/api/documentation');
})->name('l5-swagger.default.docs');
