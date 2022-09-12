<?php

namespace Modules\Savings\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Routing\Controller;
use Modules\Savings\Http\Requests\SmartGoalRequest;
use Modules\Savings\Http\Resources\SmartGoalResource;
use Modules\Savings\Models\SmartGoal;
use Modules\Savings\Services\SmartGoalService;

class SmartGoalController extends Controller
{
    use ApiResponse;

    protected SmartGoalService $smartGoalService;

    public function __construct(SmartGoalService $smartGoalService)
    {
        $this->smartGoalService = $smartGoalService;
    }

    /**
     * return list of created goals.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $goals = SmartGoal::with(['savings_wallet', 'user', 'walletHistory'])
            ->where('user_id', auth()->user()->id)
            ->get();
        return $this->successResponse('Smart Goals List', SmartGoalResource::collection($goals));
    }

    /**
     * return a specific goal
     * @param $goal_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_goal(SmartGoal $smartGoal): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse('Smart Goal List', new SmartGoalResource($smartGoal));
    }

    /**
     * Create a new goal.
     * @return \Illuminate\Http\JsonResponse
     */
    public function create_goal(SmartGoalRequest $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->validated();
        $userGoalData = array_merge($input, [
            'user_id' => auth()->user()->id,
            'status' => SmartGoal::ACTIVE,
        ]);
        $goals = $this->smartGoalService->createGoal($userGoalData);

        return $this->createdResponse('Smart goal created successfully', $goals->data);
    }

    /**
     * edit a goal.
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit_goal(SmartGoal $smartGoal): \Illuminate\Http\JsonResponse
    {
        request()->validate([
            "name" => "required|string"
        ]);
        $goal = $this->smartGoalService->editGoalName($smartGoal, request()->input("name"));
        return $this->successResponse('Smart Goals updated.', $goal->data);
    }

    /**
     * lock a goal.
     * @return \Illuminate\Http\JsonResponse
     */
    public function lock_goal(SmartGoal $smartGoal): \Illuminate\Http\JsonResponse
    {
        request()->validate([
            "duration" => "required|integer|min:12|max:52"
        ]);
        $duration = \request()->input("duration");

        $user = auth()->user()->id;

        //update goal status
        $week_from_date_locked = date("Y-m-d", strtotime("+$duration week"));
        $week = now()->addWeeks($duration);

        SmartGoal::where([
            ['id', '=', $smartGoal->id],
            ['user_id', '=', $user],
            ['status', '=', SmartGoal::ACTIVE]
        ])->update([
            'is_lock' => true,
            'is_lock_duration' => $week_from_date_locked,
            'status' => SmartGoal::LOCKED
        ]);

        return $this->successResponse('Smart Goals locked for the duration of ' . $duration . ' Weeks', []);
    }

    /**
     * pause a goal.
     * @return \Illuminate\Http\JsonResponse
     */
    public function pause_goal(SmartGoal $smartGoal): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user()->id;
        if ($smartGoal->user_id != auth()->user()->id) {
            return $this->badRequestAlert("Smart goal does not exist");
        }
        if ($smartGoal->status != SmartGoal::ACTIVE) {
            return $this->badRequestAlert("Smart goal is not active");
        }
        //update goal status
        $smartGoal->update(['status' => SmartGoal::PAUSED]);
        return $this->successResponse('Smart Goals paused.', []);
    }

    /**
     * unpause a goal.
     * @return \Illuminate\Http\JsonResponse
     */
    public function unpause_goal(SmartGoal $smartGoal): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user()->id;
        if ($smartGoal->user_id != auth()->user()->id) {
            return $this->badRequestAlert("Smart goal does not exist");
        }

        if ($smartGoal->status != SmartGoal::PAUSED) {
            return $this->badRequestAlert("Smart goal is not paused");
        }

        //update goal status
        $smartGoal->update(['status' => SmartGoal::ACTIVE]);

        return $this->successResponse('Smart Goals Unpause.', $smartGoal);
    }


    /**
     * delete a goal.
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_goal($goal_id): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user()->id;
        try {
            $goalExist = SmartGoal::where([
                ['user_id', '=', $user],
                ['status', '=', SmartGoal::ACTIVE],
                ['id', '=', $goal_id],
            ])
                ->where('can_delete', true)
                ->firstOrFail();
            $goalExist->delete();
            return $this->successResponse('Smart Goals deleted.');
        } catch (ModelNotFoundException $e) {
            return $this->badRequestAlert('Unable to delete Goal.', 'Goal not active or Invalid Goal Selected.');
        }
    }
}
