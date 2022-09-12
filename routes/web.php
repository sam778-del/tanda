<?php

use App\Http\Controllers\Mono\MonoController;
use App\Http\Controllers\Okra\OkraController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use phpseclib3\Crypt\RSA;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Okra endpoints
Route::prefix("okra")->middleware('guest')->name("okra.")->group(function () {
    Route::post('/webhook', [OkraController::class, 'webhook'])->name('webhook');
});

Route::prefix('mono')->middleware('guest')->name('mono')->group(function () {
    Route::post('/webhook', [MonoController::class, 'webhook'])->name('webhook');
});

Route::prefix("admin")->group(function() {
    // Admin Login
    Route::get('/', [LoginController::class, 'showLoginForm']);
    //Route::post('/login', [LoginController::class, 'login'])->name('login');
});

Route::group(['namespace' => 'Admin', 'middleware' => ['auth:admin']], function () {
    Route::get('/dashboard', DashboardController::class);
    Route::post('/theme-update', 'DashboardController@themeUpdate')->name('theme.update');

    //Role
    Route::prefix('roles')->group(function () {
        Route::get('/', 'RoleController@index')->name('roles.index');
        Route::get('/show/{id}/role', 'RoleController@show')->name('roles.show');
        Route::post('/create', 'RoleController@create')->name('role.create');
        Route::put('/update/{role}/show', 'RoleController@edit')->name('role.edit');
        Route::delete('/delete/{id}', 'RoleCOntroller@delete')->name('role.delete');
    });

    //Permission
    Route::prefix('permissions')->group(function() {
        Route::get('/', 'PermissionController@index')->name('permission.index');
    });

    //Customer Management
    Route::prefix('customer')->group(function() {
        Route::get('/', 'CustomerController@index')->name('customer.index');
        Route::get('/{profile}/view', 'CustomerController@customerProfile')->name('customer.profile');
        Route::put('/update/{user}/show', 'CustomerController@edit')->name('customer.update');
        Route::put('/update/{user}/status', 'CustomerController@changeStatus')->name('customer.status');
        Route::post('/update/{id}/profile', 'CustomerController@uploadProfile')->name('update.avatar');
        Route::get('/bank/{id}/account', 'CustomerController@bankAccount')->name('bank.account');
        Route::get('/{bank_name?}/{bank_code?}/transaction', 'CustomerController@bankTransactions')->name('bank.transactions');
        Route::get('/bank/{user_id}/statement', 'CustomerController@bankStatement')->name('bank.statement');
    });

    //Global Setting Page
    Route::prefix('tools')->group(function() {
        //Email Setting
        Route::prefix('email')->group(function() {
            Route::get('/', 'GlobalSettingController@emailIndex')->name('email.index');
            Route::post('/send', 'GlobalSettingCOntroller@sendMail')->name('mail.send');
        });

        //Sms Setting
        Route::prefix('sms')->group(function() {
            Route::get('/', 'GlobalSettingController@smsIndex')->name('sms.index');
            Route::post('/send', 'GlobalSettingController@sendSms')->name('sms.send');
        });

        //Notification Setting
        Route::prefix('notification')->group(function() {
            Route::get('/', 'GlobalSettingController@notifyIndex')->name('notify.index');
            Route::post('/send', 'GlobalSettingController@sendNotification')->name('notify.send');
        });

        Route::prefix('goal')->group(function() {
            Route::get('/saving/management', 'SavingGoalController@index')->name('saving.index');
            Route::put('/update/{goal}/status', 'SavingGoalController@changeStatus')->name('goal.status');
        });

        Route::prefix('rule')->group(function() {
            Route::prefix('category')->group(function() {
                Route::get('/', 'SmartRuleController@categoryIndex')->name('rule.category');
                Route::post('/store', 'SmartRuleController@categroyStore')->name('store.category');
            });
        });

        Route::prefix('saving_transaction')->group(function() {
            Route::get('/', 'SavingTransactionController@index')->name('saving_transaction.index');
            Route::get('/wallet/{savings_wallet_id}', 'SavingTransactionController@savingWallet')->name('saving.wallet');
            Route::get('/wallet/{user_id}/{user_name}', 'SavingTransactionController@savingWalletHistory')->name('saving.history');
        });

        Route::prefix('bill')->group(function() {
            Route::get('/payment', 'BillController@payment')->name('bill.payment');
            Route::get('/transaction', 'BillController@transaction')->name('bill.transaction');
            Route::get('{user_name}/{user_id}/transaction', 'BillController@userBill')->name('user.bill');
        });
    });

    Route::prefix('staff')->group(function() {
        Route::get('/', 'StaffController@index')->name('staff.index');
        Route::post('/create', 'StaffController@create')->name('staff.create');
        Route::put('/update/{admin}/status', 'StaffController@changeStatus')->name('user.status');
        Route::delete('/delete/{admin}/status', 'StaffController@deleteUser')->name('user.delete');
        Route::post('/edit/{admin}/admin', 'StaffController@edit')->name('staff.edit');
    });

    Route::get('/profile/update', 'StaffController@profileUpdate')->name('profile.update');
    Route::post('/update/{id}/profile', 'StaffController@uploadProfile')->name('profile.avatar');
    Route::post('/update/{user}/user', 'StaffController@editProfile')->name('profile.edit');
});

include_once __DIR__.'/webs.php';
