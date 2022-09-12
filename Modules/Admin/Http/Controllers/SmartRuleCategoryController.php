<?php

namespace Modules\Admin\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Models\SmartRuleCategory;
use Modules\Savings\Models\SmartRule;

class SmartRuleCategoryController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
//        $rulesCategories = SmartRuleCategory::select('id', 'name', 'description')->where('status', 'Active')->get();
        $rulesCategories = SmartRule::RULE_TYPE;
        return $this->successResponse('Smart Rule Categories retrieved successfully', $rulesCategories);
    }
}
