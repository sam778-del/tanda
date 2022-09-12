<?php

namespace Modules\Circle\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Circle\Http\Requests\StackerGuarantorRequest;
use Modules\Circle\Http\Services\StackerGuarantorService;

class StackerGuarantorController extends Controller
{
    use ApiResponse;
    protected StackerGuarantorService $stackerGuarantorService;

    public function __construct(StackerGuarantorService $stackerGuarantorService)
    {
        $this->stackerGuarantorService = $stackerGuarantorService;
    }

    /**
     * @param StackerGuarantorRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guarantyUser(StackerGuarantorRequest $request)
    {
        try {
            $stakeForUser = $this->stackerGuarantorService->stakeForUser($request->validated());
        } catch (\Exception $e) {
            return $this->serverErrorAlert($e->getMessage());
        }
        return $this->createdResponse('Operation successful', $stakeForUser);
    }
}
