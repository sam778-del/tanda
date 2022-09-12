<?php


namespace Modules\Circle\Http\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Circle\Http\Requests\ChooseSlotRequest;
use Modules\Circle\Models\Circle;
use Modules\Circle\Models\CircleMember;

class CircleMemberService
{
    /**
     * @param ChooseSlotRequest $request
     * @param Circle $circle
     * @return mixed
     */
    public function chooseCircleMemberSlot(ChooseSlotRequest $request, Circle $circle)
    {
        // Check if the slot number exist
        $getCircleMember = $this->checkIfSlotNumberExist($request, $circle);
        if (is_null($getCircleMember)) {
            throw new ModelNotFoundException("Circle slot not available");
        }

        //update the circle
        $getCircleMember->user_id = auth()->user()->id;
        $getCircleMember->save();
        return $getCircleMember;
    }

    public function swapSlot(array $request)
    {
        $request = (object) $request;

        $oldSlot = $this->getCircleMemberBySlot((int) $request->old_slot_number, (int) $request->circle_member_id);
        if ($oldSlot->has_collected ||
            is_null($oldSlot->user_id) ||
            $oldSlot->circle->start_date->diffInDays(now()->toDateString(), false) <= 0) {
            abort(400, "Customer slot cannot be swapped");
        }
        $newSlot = $this->getCircleMemberBySlot((int) $request->new_slot_number, (int) $request->circle_member_id);
        if ($newSlot->has_collected) {
            abort(400, "New Customer slot cannot be swapped");
        }
        $tempNewUser = $oldSlot->user_id;
        $tempOldUser = !is_null($newSlot->user_id) ? $newSlot->user_id : null;

        // Update the new slot number
        $newSlot->user_id = $tempNewUser;
        $newSlot->save();

        $oldSlot->user_id = $tempOldUser;
        $oldSlot->save();

        return $newSlot;
    }

    /**
     * @param int $slotNumber
     * @param int $circleMemberId
     * @return mixed
     */
    public function getCircleMemberBySlot(int $slotNumber, int $circleMemberId, $lockForUpdate = false)
    {
        return CircleMember::where('slot_number', $slotNumber)
            ->lockForUpdate()
            ->find($circleMemberId);
    }

    private function checkIfSlotNumberExist(ChooseSlotRequest $request, Circle $circle)
    {
        return CircleMember::join('circles', 'circle_members.id', '=', 'circles.id')
            ->select('circle_members.*')
            ->where('circles.status', $request->status)
            ->where('circles.start_date', '>', now()->toDateString())
            ->where('circle_id', $circle->id)
            ->whereNull('user_id')
            ->where('slot_number', $request->slot_number)
            ->lockForUpdate()
            ->first();
    }
}
