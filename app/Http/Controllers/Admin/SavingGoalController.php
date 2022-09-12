<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\SavingGoal;

class SavingGoalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        if(auth()->user()->can('Manage Savings Goal Management'))
        {
            $goal = SavingGoal::orderBy('id', 'DESC')->get();
            return view('goal.index')->with(["smart_goal" => $goal]);
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    public function changeStatus(SavingGoal $goal){
        if(auth()->user()->can('Edit Savings Goal Management')){
            $status = $goal->status == "Active" ? SavingGoal::DISABLE : SavingGoal::ENABLE;
            $goal->status = $status;
            $goal->save();
            return redirect()->back()->with('success', __('Saving Goal Status Changed Succesfully'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }
}
