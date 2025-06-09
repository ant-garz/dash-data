<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

// routes/api.php
Route::get('/test', function () {
    return response()->json(['status' => 'ok']);
});
