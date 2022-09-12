<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\BankController;
use Modules\Wallet\Http\Controllers\CardController;
use Modules\Wallet\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//User wallet
Route::prefix('wallets')
    ->name('wallet.')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::get('/balance', [WalletController::class, 'index'])->name('index');
        Route::get('/history', [WalletController::class, 'walletHistory'])->name('history');
        Route::post('/fund', [WalletController::class, 'fundWallet'])->name('fund');
    });

//User cards
// Route::prefix('cards')
//     ->name('cards.')
//     ->middleware(['auth:api'])
//     ->group(function () {
//         Route::get('/', [CardController::class, 'index'])->name('index');
//         Route::get('/{userCard}', [CardController::class, 'index'])->name('index');
//         Route::post('/add', [CardController::class, 'create'])->name('create');
//         Route::post('/validate-charge', [CardController::class, 'validateCharge'])->name('validate.charge');
//         Route::post('/make_card_default', [CardController::class, 'makeCardDefault'])->name('default');
//         Route::post('/delete_card', [CardController::class, 'delete'])->name('delete');
//     });

// User banks
Route::prefix('banks')
    ->name('banks.')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::get('/', [BankController::class, 'index'])->name('index');
        Route::post('add_bank_details', [BankController::class, 'add_bank_account'])->name('add_bank_details');
        Route::post('request_payout/{bankid}', [BankController::class, 'payout_request'])->name('payout_request');
        Route::get('get_banks', [BankController::class, 'get_banks'])->name('get_banks');
    });
