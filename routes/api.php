<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;

// routes/api.php
Route::get('/test', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware(['web', 'auth'])->group(function () {
    // Users
    Route::put('/user', [UserController::class, 'update']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);


    // Videos
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/videos/{video}', [VideoController::class, 'show']);
});
