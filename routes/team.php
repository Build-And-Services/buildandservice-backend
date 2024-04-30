<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/teams')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\TeamController::class, 'index']);
    Route::post('/',[\App\Http\Controllers\Api\TeamController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\Api\TeamController::class, 'show']);
    Route::post('/{id}', [\App\Http\Controllers\Api\TeamController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\TeamController::class, 'destroy']);
});
