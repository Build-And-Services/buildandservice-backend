<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/consultations')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\ConsultationController::class, 'index']);
    Route::post('/',[\App\Http\Controllers\Api\ConsultationController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\Api\ConsultationController::class, 'show']);
    Route::post('/{id}', [\App\Http\Controllers\Api\ConsultationController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\ConsultationController::class, 'destroy']);
});
