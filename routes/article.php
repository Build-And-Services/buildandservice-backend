<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/articles')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\ArticleController::class, 'index']);
    Route::post('/',[\App\Http\Controllers\Api\ArticleController::class, 'store']);
    Route::get('/{slug}', [\App\Http\Controllers\Api\ArticleController::class, 'show']);
    Route::post('/{id}', [\App\Http\Controllers\Api\ArticleController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\ArticleController::class, 'destroy']);
});
