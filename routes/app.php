<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    LoginController,
    RegisterController,
    VerifyEmailController,
    OrganizationController,
    EntityController,
    EmployeeController,
    PlanController,
    SettingController
};

Route::prefix('auth')->name('auth.')->group(function () {
    //google
    Route::get('redirect/{provider}', [RegisterController::class, 'redirect']);
    Route::get('redirect/callback/{provider}', [RegisterController::class, 'callback']);

    Route::post('register', [RegisterController::class, 'registerEmployer'])->name("register.user");
    // login
    Route::post('login', [LoginController::class, 'login'])->name('login.user');
    // Verify email
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->name('verification.verify');

    // Resend link to verify email
    Route::post('/email/verify/resend', [VerifyEmailController::class, 'resendVerifcation'])->middleware(['auth:employer', 'throttle:6,1'])->name('verification.send');

    // Organization Details
    Route::post('/organization/details', [OrganizationController::class, 'create'])->name('organization.create');

    //Entity  Details
    Route::post('/entity/details', [EntityController::class, 'create'])->name('entity.create');

    //Personal Details
    Route::post('/personal/details', [EntityController::class, 'createPersonalDetails'])->name('personal.details.create');

    //Pay Schedule
    Route::post('/pay/schedule', [EntityController::class, 'paySchedule'])->name('pay.schedule');
});

Route::prefix('plans')->middleware('auth:employer')->name('plans')->group(function() {
    Route::get('/all', [PlanController::class, 'allPlan'])->name('all.list');
    Route::get('/current', [PlanController::class, 'index'])->name('current.list');
});

Route::prefix('setting')->middleware('auth:employer')->name('settings')->group(function() {
    Route::prefix('personal')->group(function () {
       Route::get('/get', [SettingController::class, 'getPersonalDetails']);
       Route::post('/details', [SettingController::class, 'personalDetails']);
       Route::post('/password', [SettingController::class, 'updatePassword']);
    });

    Route::prefix('company')->group(function() {
        Route::post('office/phone', [SettingController::class, 'officePhone']);
        Route::post('change/address', [SettingController::class, 'changeAddress']);
    });
});

Route::prefix('employee')->middleware('auth:employer')->name('employees.')->group(function() {
    Route::post('/single/add', [EmployeeController::class, 'addSingleEmployee'])->name('add.single');
    Route::post('/salary/{id}/details', [EmployeeController::class, 'salaryDetails'])->name('salary.details');
    Route::post('/multiple/add', [EmployeeController::class, 'addMultipleEmployee'])->name('add.multiple');
    Route::get('/all/list', [EmployeeController::class, 'getAllEmployees'])->name('list.employees');
    Route::get('/active/list', [EmployeeController::class, 'getActiveEmployeeList'])->name('active.employees');
    Route::get('/terminated/list', [EmployeeController::class, 'getTerminatedEmployeeList'])->name('terminated.employees');
    Route::get('/search/list', [EmployeeController::class, 'searchEmployeeList'])->name('search.employees');

    //Excel Import
    Route::prefix('excel')->group(function () {
        Route::post('/import/file', [EmployeeController::class, 'importFile'])->name('import.file');
        Route::get('/ready/import', [EmployeeController::class, 'readyImport'])->name('ready.import');
        Route::post('/import/selected', [EmployeeController::class, 'importSelected'])->name('import.selected');
    });
});

Route::fallback(function () {
    return response()->json(['error' => 'Not Found!'], 404);
});
