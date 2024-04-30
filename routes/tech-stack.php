<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/tech-stacks')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\TechStackController::class, 'index']);
    Route::post('/',[\App\Http\Controllers\Api\TechStackController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\Api\TechStackController::class, 'show']);
    Route::post('/{id}', [\App\Http\Controllers\Api\TechStackController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\TechStackController::class, 'destroy']);
});
