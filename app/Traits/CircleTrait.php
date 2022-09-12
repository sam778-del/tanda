<?php


namespace App\Traits;


use App\Models\UserStaker;
use Carbon\Carbon;
use Modules\Circle\Http\Requests\JoinCircleRequest;
use Modules\Circle\Models\Circle;

trait CircleTrait
{
    private function processPayoutDate(Circle $circle, int $i)
    {
        return $i == 0 ? $circle->start_date->endOfMonth()->toDateString() :
            $circle->start_date->endOfMonth()->addMonthsNoOverflow($i+1);
    }

    private function processEndDate(JoinCircleRequest $request): Carbon
    {
        return Carbon::parse($request->start_date)->addMonthsNoOverflow((int) $request->circle_size);
    }

    private function checkIfUserHasStackers(): int
    {
        return UserStaker::where('user_id', auth()->user()->id)->count();
    }
}
