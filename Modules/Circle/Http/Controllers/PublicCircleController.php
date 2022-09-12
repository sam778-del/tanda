<?php


namespace Modules\Circle\Http\Controllers;


use App\Traits\ApiResponse;
use Modules\Circle\Http\Requests\JoinCircleRequest;
use Modules\Circle\Http\Services\CircleService;
use Modules\Circle\Models\Circle;

class PublicCircleController
{
    use ApiResponse;

    protected CircleService $circleService;

    public function __construct(CircleService $circleService)
    {
        $this->circleService = $circleService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse(
            "Active circles successful",
            $this->circleService->getPublicCircles()
        );
    }

    /**
     * @param Circle $circle
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Circle $circle): \Illuminate\Http\JsonResponse
    {
        $circleDetail = $this->circleService->viewCircle($circle, Circle::PUBLIC);
        if ($circleDetail['status']) {
            return $this->successResponse('success', $circleDetail['data']);
        }
        return $this->badRequestAlert($circleDetail['message']);
    }

    public function joinCircle(JoinCircleRequest $request)
    {

    }
}
