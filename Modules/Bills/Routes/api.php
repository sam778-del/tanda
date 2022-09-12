<?php



use Illuminate\Support\Facades\Route;
use Modules\Bills\Http\Controllers\BillsController;

Route::prefix('bills')
    ->name('bills.')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::get('/{service}', [BillsController::class, 'index'])->name('index');
        Route::post('/verify', [BillsController::class, 'verifyService'])->name('verify');
        Route::post('/purchase', [BillsController::class, 'purchase'])->name('purchase');
    });
