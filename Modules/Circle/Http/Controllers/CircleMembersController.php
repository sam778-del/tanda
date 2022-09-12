<?php

namespace Modules\Circle\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Circle\Http\Requests\ChangeSlotRequest;
use Modules\Circle\Http\Requests\ChooseSlotRequest;
use Modules\Circle\Http\Services\CircleMemberService;
use Modules\Circle\Models\Circle;
use Modules\Circle\Models\CircleMember;

class CircleMembersController extends Controller
{
    use ApiResponse;

    protected CircleMemberService $circleMemberService;

    public function __construct(CircleMemberService $circleMemberService)
    {
        $this->circleMemberService = $circleMemberService;
    }

    /**
     * @param ChooseSlotRequest $request
     * @param Circle $circle
     * @return \Illuminate\Http\JsonResponse
     */
    public function chooseSlot(ChooseSlotRequest $request, Circle $circle): \Illuminate\Http\JsonResponse
    {
        $getSlot = $this->circleMemberService->chooseCircleMemberSlot($request, $circle);
        return $this->successResponse('Slot added successfully', $getSlot);
    }

    /**
     * @param ChangeSlotRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function swapSlotPosition(ChangeSlotRequest $request, CircleMember $circleMember): \Illuminate\Http\JsonResponse
    {
        $request->merge([
            'circle_member_id' => $circleMember->id,
        ]);
        $swapSlot = $this->circleMemberService->swapSlot($request->toArray());
        return $this->successResponse('Operation successful', $swapSlot);
    }
}
