<?php

use App\Http\Controllers\AuthController;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/login', function () {
    return Inertia::render('Login');
});

Route::get('/register', function () {
    return Inertia::render('Register');
});

Route::get('/profile', function () {
    return Inertia::render('ProfileView');
});

Route::get('/profile/edit', function () {
    return Inertia::render('ProfileForm');
});

Route::get('/profile/edit/password', function () {
    return Inertia::render('PasswordForm');
});



Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:login');
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/user', function (\Illuminate\Http\Request $request) {
    return $request->user();
});