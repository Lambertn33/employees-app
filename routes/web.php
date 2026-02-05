<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/openapi.json', function () {
    $path = storage_path('app/openapi.json');
    abort_unless(file_exists($path), 404);

    return response()->file($path, [
        'Content-Type' => 'application/json',
    ]);
});
