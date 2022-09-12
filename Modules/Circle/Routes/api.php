<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Circle\Http\Controllers\PrivateCircleController;
use Modules\Circle\Http\Controllers\PublicCircleController;


Route::prefix('circles/public')
    ->name('circle.public.')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::get('/', [PublicCircleController::class, 'index'])->name('index');
        Route::get('/{circle}', [PublicCircleController::class, 'show'])->name('show');
        Route::post('join-circle/{circle}', [PublicCircleController::class, 'joinCircle'])->name('join.circle');
    });


Route::prefix('circles/private')
    ->name('circle.private.')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::get('/', [PrivateCircleController::class, 'index'])->name('index');
        Route::get('/{circle}', [PrivateCircleController::class, 'show'])->name('show');
    });
