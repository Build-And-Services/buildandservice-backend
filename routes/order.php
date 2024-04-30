<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/orders')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::patch('/{id}', [\App\Http\Controllers\Api\OrderController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\OrderController::class, 'destroy']);
});
