<?php

use Illuminate\Support\Facades\Route;

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'name' => 'Svelte Developer'
    ]);
});


Route::get('/login', function () {
    return Inertia::render('Login', [
        'name' => 'Svelte Developer'
    ]);
});
