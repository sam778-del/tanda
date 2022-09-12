<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Jobs\EmailOtpJob;
use App\Jobs\SmsOtpJob;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Services\AuthService;
use App\Services\BankService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Classes\BulkSms;

class RegisterController extends Controller
{
    private $authService;
    private $bankService;
    private $smsService;

    public function __construct()
    {
        $this->authService = resolve(AuthService::class);
        $this->bankService = resolve(BankService::class);
        $this->smsService  = resolve(BulkSms::class);
    }

    /**
     * Register new user and send otp to them
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerUser(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->authService->registerUser($request->validated());
        if (empty($user)) {
            abort(400, "User creation failed, please try again");
        }
        //Send OTP verification to user
        $this->dispatchOtpToUser($user);

        $payload = [
            "transaction" =>  [
                "reference" => $this->generateBvn()
            ],
            "order" => [
                "description" => "yes",
                "country" => "NG"
            ],
            "customer" => [
                "account" => [
                    "name" => Str::ucfirst($user->first_name.' '.$user->last_name),
                    "type" => "STATIC"
                ]
            ]
        ];

        $account = $this->bankService->CreateVirtualAccount($payload)["customer"]["account"];

        $customerInfo = [
            "customer" => [
                "account" => [
                    "number" => $account["number"],
                    "bank" => "999255"
                ]
            ]
        ];
        $data    = $this->bankService->GetVirtualAccount($customerInfo);
        return response()->json($data);

        // $virtual_account_id = $this->monoService->CreateVirtualAccount($virtual_account_payload)["data"]["id"];

        // $data = $this->monoService->GetVirtualAccount($virtual_account_id)["data"];
        // $data["user_id"] = $user->id;

        // VirtualAccount::create($data);

        // return $this->createdResponse("User created successfully");

    }

    private function generateBvn(){
        return strtoupper(str_replace('.', '', uniqid('', true)));
    }

    /**
     * Dispatch both SMS and Email OTP to User
     *
     * @param User $user
     */
    private function dispatchOtpToUser(User $user)
    {
        try {
            $numberOfDigits = 5;
            $activationCode = substr(str_shuffle("0123456789"), 0, $numberOfDigits);
            Cache::put($user->id . "_activation_code", $activationCode, now()->addMinutes(15));
            $text = "Tanda activation pin: " . $activationCode;
            $this->smsService->sendSms($activationCode, $user->phone_no);
            //EmailOtpJob::dispatch($user, $activationCode);
        } catch (\Exception $e) {
            abort(400, $e->getMessage());
        }
    }
}
