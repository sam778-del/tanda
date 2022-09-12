<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePinCodeRequest;
use App\Jobs\EmailOtpJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Savings\Models\SmartGoal;

class PinController extends Controller
{
    /**
     * @param CreatePinCodeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPinCode(CreatePinCodeRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)
                ->first();

            // Update customer credentials
            $user->update([
                'pin_code' => $request->pin_code,
                'status' => User::ENABLE,
            ]);

            //create a wallet for the user
            $user->wallet()->create([
                'user_id' => $user->id,
                'initial_amount' => 0,
                'actual_amount' => 0,
            ]);


            $tokenResult = $user->createToken('web-api-token')->accessToken;
            $payload = [
                'access_token' => $tokenResult,
                'user' => $user,
                'token_type' => 'Bearer',
            ];

            //create default system goal
            $system_goal_data = [
                'user_id' => $user->id,
                'name' => 'Reserve Funds',
                'colour_code' => '#ff0030',
                'can_delete' => false
            ];

            //create goal
            $smartGoal = SmartGoal::create($system_goal_data);

            //create goal wallet
            $smartGoal->savings_wallet()->create([
                'initial_amount' => 0,
                'actual_amount' => 0,
                'user_id' => $user->id,
                'smart_goal_id' => $smartGoal->id
            ]);


            DB::commit();

            return $this->createdResponse('Pin code created successfully', $payload);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->serverErrorAlert($exception->getMessage(), $exception);
        }
    }

    public function verify(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['pin' => 'required|digits:4']);
        $verifyPin = Hash::check($request->pin, auth()->user()->pin_code);
        if ($verifyPin) {
            return $this->successResponse('Verification successful');
        }
        return $this->badRequestAlert('Verification failed');
    }
}
