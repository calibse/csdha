<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/attendance/{event_date}', 
        [AttendanceController::class, 'store']);
});

Route::domain(config('app.user_domain'))->group(function () {

    Route::get('/stream/home', [HomeController::class, 'stream']);
});

