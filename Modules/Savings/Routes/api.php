<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\SmartRuleCategoryController;
use Modules\Savings\Http\Controllers\SmartRuleController;
use Modules\Savings\Http\Controllers\SavingsController;
use Modules\Savings\Http\Controllers\SmartGoalController;

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

Route::middleware('auth:api')->get('/savings', function (Request $request) {
    return $request->user();
});

//savings
Route::prefix("savings")->middleware(['auth:api'])->name("savings.")->group(function () {
    Route::get('/get_savings', [SavingsController::class, 'index'])->name("get_savings");
    Route::post('/withdraw_amount', [SavingsController::class, 'withdrawAmount'])->name("withdrawAmount");

    //Goals
    Route::get('/get_goals', [SmartGoalController::class, 'index'])->name("get_goals");
    Route::get('/smart-goal/{smartGoal}', [SmartGoalController::class, 'get_goal'])->name("get_goal");
    Route::post('/create_goal', [SmartGoalController::class, 'create_goal'])->name("create_goal");
    Route::patch('/edit_goal/{smartGoal}', [SmartGoalController::class, 'edit_goal'])->name("edit_goal");
    Route::put('/lock_goal/{smartGoal}', [SmartGoalController::class, 'lock_goal'])->name("lock_goal");
    Route::put('/pause_goal/{smartGoal}', [SmartGoalController::class, 'pause_goal'])->name("pause_goal");
    Route::patch('/unpause_goal/{smartGoal}', [SmartGoalController::class, 'unpause_goal'])->name("unpause_goal");
    Route::delete('/delete_goal/{smartGoal}', [SmartGoalController::class, 'delete_goal'])->name("delete_goal");

    Route::post('/create_rule', [SmartRuleController::class, 'create_rule'])->name("create_rule");
    Route::get('/get_rules', [SmartRuleCategoryController::class, 'index'])->name("get_rules");
    
});
