<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('app');
});

Route::get('/contact/new', function () {
    return view('app');
});

Route::get('/contact/{id}', function () {
    return view('app');
});

Route::get('/contact/{id}/edit', function () {
    return view('app');
});

Route::get('/api/contacts', [ContactController::class, 'index']);

Route::get('/api/contact/{id}', [ContactController::class, 'show']);

Route::post('/api/contact', [ContactController::class, 'update']);

Route::put('/api/contact/{id}', [ContactController::class, 'update']);

Route::delete('/api/contact/{id}', [ContactController::class, 'destroy']);