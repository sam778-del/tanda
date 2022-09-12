<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmartRuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function categoryIndex(){
        if(auth()->user()->can('Manage Smart Rule Management')){
            $rule_category = DB::table('smart_rules_categories')->orderBy('id', 'DESC')->get();
            return view('rule.category.index')->with(["category" => $rule_category]);
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }
}
