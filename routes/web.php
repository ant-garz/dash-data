<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// unauthenticated views
Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/login', function () {
    return Inertia::render('Login');
})->name("login");

Route::get('/register', function () {
    return Inertia::render('Register');
})->name("register");

// unauthenticated actions
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name("postLogin");
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:login')->name("postRegister");
Route::post('/logout', [AuthController::class, 'logout'])->name("postLogout");


// ✅ Protected routes (auth required)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); // you *want* auth for logout, but it's often handled via CSRF token
    // Profile
    Route::get('/profile', fn () => Inertia::render('ProfileView'));
    Route::get('/profile/edit', fn () => Inertia::render('ProfileForm'));
    Route::get('/profile/edit/password', fn () => Inertia::render('PasswordForm'));

    // Videos
    Route::get('/videos', fn () => Inertia::render('Videos'));
    Route::get('/videos/create', fn () => Inertia::render('CreateVideo'));

    Route::get('/videos/{id}', fn (string $id) => Inertia::render('Video', [
        'id' => $id
    ]));

    Route::get('/videos/{id}/edit', fn (string $id) => Inertia::render('VideoEditor', [
        'id' => $id
    ]));

    Route::get('/videos/{id}/upload-segments', fn (string $id) => Inertia::render('SegmentUploader', [
        'id' => $id
    ]));
});
