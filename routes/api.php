<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/v1')->group(function () {
    include __DIR__ . '/team.php';
    include __DIR__ . '/tech-stack.php';
    include __DIR__ . '/consultation.php';
    include __DIR__ . '/order.php';
    include __DIR__ . '/article.php';
});
