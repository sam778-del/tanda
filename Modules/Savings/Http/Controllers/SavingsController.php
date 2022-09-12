<?php

namespace Modules\Savings\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Savings\Http\Requests\SmartLockRequest;
use Modules\Savings\Http\Requests\SmartSaveRequest;
use Modules\Savings\Http\Requests\WithdrawAmountRequest;
use Modules\Savings\Models\SmartLock;
use Modules\Savings\Models\SmartSave;
use Modules\Savings\Models\SmartVest;
use Modules\Savings\Services\SavingsService;

class SavingsController extends Controller
{
    use ApiResponse;

    public SavingsService $savings;

    public function __construct(SavingsService $savings)
    {
        $this->savings = $savings;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $payload = [
            'smart_save' => $this->savings->getUserSmartSave(),
            'smart_lock' => $this->savings->getUserSmartLock(),
            'smart_vest' => $this->savings->getUserSmartVest()
        ];

        return $this->successResponse('Savings retrieved successfully', $payload);
    }

    public function withdrawAmount(WithdrawAmountRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->savings->withdrawAmount($request->amount, $request->wallet_id,auth()->user());
        return $this->successResponse('Withdrawal Successful');
    }

}
