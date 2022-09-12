<?php

namespace Modules\Circle\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Modules\Circle\Http\Requests\ChooseSlotRequest;
use Modules\Circle\Http\Requests\CompanyRequest;
use Modules\Circle\Http\Requests\JoinCircleRequest;
use Modules\Circle\Http\Requests\LeaveCircleRequest;
use Modules\Circle\Http\Services\CircleService;
use Modules\Circle\Http\Services\CompanyService;
use Modules\Circle\Models\Circle;
use Modules\Circle\Models\CircleMember;
use Modules\Circle\Models\Company;

class PrivateCircleController extends Controller
{
    use ApiResponse;

    protected CircleService $circleService;

    public function __construct(CircleService $circleService)
    {
        $this->circleService = $circleService;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse(
            "Active circles successful",
            $this->circleService->getPrivateCircles()
        );
    }

    /**
     * Api to create a company
     *
     * @param JoinCircleRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(JoinCircleRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->circleService->createPrivateCircle((object)$request->validated());
        return $this->createdResponse('Circle created successfully');
    }

    /**
     * @param LeaveCircleRequest $request
     * @param Circle $circle
     * @return \Illuminate\Http\JsonResponse
     */
    public function leaveCircle(LeaveCircleRequest $request, Circle $circle): \Illuminate\Http\JsonResponse
    {
        $request->merge([
            'status' => CircleMember::INACTIVE,
            'circle_id' => $circle->id
        ]);

        // CHeck if user can leave the circle
        $checkCircleStatus = $this->circleService->checkIfUserCanLeaveCircle($request->toArray());
        if (!Arr::get($checkCircleStatus, 'status')) {
            return $this->badRequestAlert($checkCircleStatus['message']);
        }
        $this->circleService->leaveCircle();
        return $this->successResponse('Operation successful');
    }

    /**
     * @param Circle $circle
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Circle $circle): \Illuminate\Http\JsonResponse
    {
        $circleDetail = $this->circleService->viewCircle($circle, Circle::PRIVATE);
        if ($circleDetail['status']) {
            return $this->successResponse($circleDetail['data']);
        }
        return $this->badRequestAlert($circleDetail['message']);
    }

    public function join(JoinCircleRequest $request): \Illuminate\Http\JsonResponse
    {
        $join = $this->circleService->joinCircle($request->validated());
        if (Arr::get($join, 'status')) {
            return $this->successResponse('Operation successful', $join['data']);
        }
    }
}
