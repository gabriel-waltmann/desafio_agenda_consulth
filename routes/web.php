<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('app');
});

Route::get('/contact/new', function () {
    return view('app');
});


Route::get('/api/contacts', [ContactController::class, 'index']);

Route::post('/api/contact', [ContactController::class, 'store']);

Route::delete('/api/contact/{id}', [ContactController::class, 'destroy']);