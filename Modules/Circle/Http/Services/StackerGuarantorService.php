<?php


namespace Modules\Circle\Http\Services;

use Exception;
use Modules\Circle\Models\CircleMember;
use Modules\Circle\Models\StackerGuarantor;

class StackerGuarantorService
{
    /**
     * @param array $request
     * @throws Exception
     */
    public function stakeForUser(array $request)
    {
        $getStackers = $this->checkIfUserHasStackers($request);
        // Check if the user already has at least 3 stackers
        if ($getStackers->count() >= 3) {
            throw new Exception('Stackers has already been filled for this user');
        }

        // Create the stacker as a guarantor for the user
        return StackerGuarantor::create([
            'user_id' => $request->user_id,
            'circle_id' => $request->circle_id,
            'circle_member_id' => $request->circle_member_id,
            'guarantor_id' => auth()->user()->id
        ]);
    }

    /**
     * @param array $request
     * @throws Exception
     */
    private function checkIfUserHasStackers(array $request)
    {
        // Get the circle member
        $circleMember = $this->getCircleMember($request);
        if (is_null($circleMember)) {
            throw new Exception('User not available for stacking');
        }
        return StackerGuarantor::where('circle_id', $request['circle_id'])
            ->where('user_id', $request['user_id'])
            ->where('circle_member_id', $circleMember->id)
            ->get();
    }

    /**
     * @param array $request
     * @return mixed
     */
    private function getCircleMember(array $request)
    {
        return CircleMember::where('circle_id', $request['circle_id'])
            ->where('user_id', $request->user_id)
            ->where('status', CircleMember::PENDING)
            ->first();
    }
}
