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
    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
    
    // Videos
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/videos/{video}', [VideoController::class, 'show']);

     // Step 1: Create Video
    Route::post('/videos', [VideoController::class, 'store']);

    // Step 2: Upload Segments for a specific video
    Route::post('/videos/{video}/segments', [VideoController::class, 'uploadSegments']);

    // Step 3: Stitch Video
    Route::post('/videos/{video}/stitch', [VideoController::class, 'stitch']);

    // Step 4: Update Video Metadata
    Route::patch('/videos/{video}', [VideoController::class, 'update']);

    // cancel / delete associated data
    Route::delete('/videos/{video}/cancel', [VideoController::class, 'cancelUpload']);
});
