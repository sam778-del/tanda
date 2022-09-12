<?php

namespace Modules\Savings\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Savings\Http\Requests\SmartRuleRequest;
use Modules\Savings\Models\SmartRule;
use Modules\Savings\Services\SmartRuleService;

class SmartRuleController extends Controller
{
    use ApiResponse;

    protected SmartRuleService $smartRuleService;

    public function __construct(SmartRuleService $smartRuleService)
    {
        $this->smartRuleService = $smartRuleService;
    }


    public function create_rule(SmartRuleRequest $request)
    {
        $input = $request->toArray();
        $userRulesData = array_merge($input, [
            'user_id' => auth()->user()->id,
            'deduction_date' => now()
        ]);
        $smartRule = $this->smartRuleService->createRules($userRulesData);

        return $this->createdResponse('Smart goal created successfully', $smartRule['data']);
    }
}
