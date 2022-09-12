<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiometricController;
use App\Http\Controllers\Mono\MonoController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserStackerController;
use Illuminate\Support\Facades\Route;


Route::get('test_dynamic_link', function () {
//    return \Carbon\Carbon::parse('2021-10-09 15:04:46')->format("D, jS F Y ");
//    return auth()->user()->wallet->actual_amount;
    $amount = 8500;
    $nearestValue = 1000;
    return (round(($amount+$nearestValue/2)/$nearestValue)*$nearestValue) - $amount;
});

// Route::prefix("auth")->name("auth.")->group(function () {
//    // Route::post('register', [RegisterController::class, 'registerUser'])->name("register.user");
//     Route::post('create-pin', [PinController::class, 'createPinCode'])->name("pin.add");
//     Route::post('/biometric/create', [BiometricController::class, 'store'])->name('add');

//    Route::post('add-phone', [RegisterControllerOld::class, 'addPhoneNumber'])->name("add.phone");
//    Route::post('verify-phone', [RegisterControllerOld::class, 'verifyPhoneNumber'])->name("verify.phone");
//    Route::post('add-email/{user}', [RegisterControllerOld::class, 'addEmail'])->name("add.email");
//    Route::post('verify-email', [RegisterControllerOld::class, 'verifyEmail'])->name("verify.email");
//    Route::post('add-password', [RegisterControllerOld::class, 'addPassword'])->name("add.password");
//    Route::post('add-profile', [RegisterControllerOld::class, 'addProfile'])->name("add.profile");

//    Route::post('login', [AuthController::class, 'login'])->name("login");
//    Route::post('activate_account', [CustomerController::class, 'activateAccount'])->name("activate_account");
//    Route::post('resend-code', [RegisterControllerOld::class, 'resendCode'])->name("resend_code");

// });

// Route::prefix("forgot_password")->group(function () {
//     Route::post("/", [AuthController::class,'forgotPassword'])->name("forgot_password");
//     Route::post("reset", [AuthController::class, 'userResetPassword'])->name("reset_password");
// });

// // Okra endpoints
// Route::prefix("mono")->name("mono.")->group(function () {
//     Route::post('/webhook', [MonoController::class, 'webhook'])->name('webhook');
//     Route::post('/verify-token', [MonoController::class, 'verifyMonoToken'])->name('verify.token');
// });

// Route::middleware(['auth:api'])->group(function () {
//     //Mono Changes
//     Route::post('physical-cards/fund',[PhysicalCardController::class,'FundWallet']);
//     Route::apiResource('physical-cards',PhysicalCardController::class);
//     Route::post('virtual-cards/fund',[VirtualCardController::class,'FundWallet']);
//     Route::apiResource('virtual-cards',VirtualCardController::class);

//     Route::prefix('biometric')->middleware('biometric_check')->name('biometric.')->group(function () {
//         Route::post('/disable', [BiometricController::class, 'disable'])->name('disable');
//     });

//     Route::prefix('stackers')->name('stackers.')->group(function () {
//         Route::get('/users/{user}', [UserStackerController::class, 'index'])->name('user');
//     });

//     Route::prefix('pin')->name('pin.')->group(function () {
//         Route::post('/verify', [PinController::class, 'verify'])->name('verify');
//     });

//     Route::prefix('user')->name('user')->group(function () {
//         Route::get('/', [ProfileController::class, 'index'])->name('index');
//         Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('password.update');
//     });

//     Route::get('transfer-limit', [ProfileController::class, 'transferLimits'])->name('transfer.limit');
//     Route::get('get-support', [ProfileController::class, 'getSupport'])->name('get.support');
// });

//Wallet



Route::fallback(function () {
    return response()->json(['error' => 'Not Found!'], 404);
});
