<?php


namespace Modules\Circle\Http\Services;

use App\Traits\ApiResponse;
use App\Traits\CircleTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Circle\Models\Circle;
use Modules\Circle\Models\CircleMember;

class CircleService
{
    use CircleTrait, ApiResponse;

    public function getPublicCircles()
    {
        return Circle::where('start_date', '>', now()->toDateString())
            ->where('type', Circle::PUBLIC)
            ->oldest()
//            ->limit(3)
            ->get();
//            ->groupBy('circle_size')->flatten()->all();
    }

    public function getPrivateCircles()
    {
        return CircleMember::select('circles.*')
            ->join('circles', 'circle_members.id', '=', 'circles.id')
            ->where('circles.start_date', '>', now()->toDateString())
            ->where('circle_members.user_id', auth()->user()->id)
            ->get();
    }

    public function viewCircle(Circle $circle, string $type)
    {
        $viewCircle = Circle::with('circleMembers')
            ->where('type', $type)
            ->find($circle->id);

        if (!empty($viewCircle)) {
            return $this->ok($viewCircle);
        }
        return $this->bad('Circle not found');
    }

    /**
     * @param object $request
     * @return mixed
     * @throws Exception
     */
    public function createPrivateCircle(object $request)
    {
        DB::beginTransaction();
        try {
            // create circle
            $circles = Circle::create([
                'description' => $request->description,
                'name' => $request->name,
                'type' => Circle::PRIVATE,
                'user_id' => auth()->user()->id,
                'circle_amount' => $request->circle_amount,
                'circle_size' => $request->circle_size,
                'start_date' => $request->start_date,
                'end_date' => $this->processEndDate($request),
                'fee' => $request->fee,
            ]);

            // Create circle members based on the size
            $circleMembers = [];
            $dateCreated = now();
            for ($i = 0; $i < (int)$request->circle_size; $i++) {
                $circleMembers[] = [
                    'circle_id' => $circles->id,
                    'payout_date' => $this->processPayoutDate($circles, $i),
                    'slot_number' => $i + 1,
                    'status' => CircleMember::PENDING,
                    'created_at' => $dateCreated,
                    'updated_at' => $dateCreated,
                ];
            }

            // create circle member with empty slot
            CircleMember::insert($circleMembers);
            DB::commit();
            return $this->ok($circles);
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->bad('Operation failed');
        }
    }

    public function leaveCircle(CircleMember $circleMember): CircleMember
    {
        $circleMember->status = CircleMember::INACTIVE;
        $circleMember->save();
        return $circleMember;
    }

    public function checkIfUserCanLeaveCircle(array $request)
    {
        $data =  CircleMember::where('user_id', auth()->user()->id)
            ->where('circle_id', $request['circle_id'])
            ->where('stats', CircleMember::PENDING)
            ->where('has_collected', false)
            ->first();
        if (!empty($join)) {
            return $this->ok($data);
        }
        return $this->bad('Sorry you cannot leave circle again');
    }

    public function joinCircle(array $request)
    {
        $request = (object) $request;
        $circle = Circle::with('circleMembers')->find($request->circle_id);
        if ($circle->start_date->diffInDays(now()->toDateString()) < 0) {
            abort(400, "Circle has expired");
        }
        // Check if slot has been taken
        if ($circle->circleMembers->slot_number == $request->slot_number &&
            !is_null($circle->circleMembers->user_id)) {
            abort(400, "Sorry this slot has been taken");
        }

        $circle->circleMembers->update([
            'user_id' => auth()->user()->id,
            'payment_method' => $request->payment_method,
            'status' => CircleMember::ACCEPTED
        ]);

        return $this->ok($circle->circleMembers);
    }
}
