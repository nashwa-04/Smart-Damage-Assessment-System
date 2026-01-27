<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Health check endpoint to test API connection
 * @response 200 {"status":"ok","message":"API is running","version":"1.0.0"}
 */
Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String()
    ]);
});

Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [App\Http\Controllers\Api\AuthController::class, 'me'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/reports', [App\Http\Controllers\Api\ReportController::class, 'index']);
    Route::post('/reports', [App\Http\Controllers\Api\ReportController::class, 'store']);
    Route::get('/reports/{id}', [App\Http\Controllers\Api\ReportController::class, 'show']);
    Route::delete('/reports/{id}', [App\Http\Controllers\Api\ReportController::class, 'destroy']);
});
