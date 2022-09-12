<?php

namespace Modules\Merchant\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Merchant\Http\Requests\MerchantContactRequest;
use Modules\Merchant\Http\Requests\MerchantDetailsRequest;
use Modules\Merchant\Http\Requests\MerchantPersonalDetailsRequest;
use Modules\Merchant\Http\Services\RegisterServices;
use Modules\Merchant\Models\Merchant;

class RegisterController extends Controller
{
    use ApiResponse;
    /**
     * @var RegisterServices
     */
    private RegisterServices $registerServices;

    public function __construct(RegisterServices $registerServices)
    {
        $this->registerServices = $registerServices;
    }

    /**
     * @param MerchantContactRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerMerchantContact(MerchantContactRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->createdResponse(
            'Merchant contact created successfully',
            $this->registerServices->storeMerchantInSession($request->validated())
        );
    }

    /**
     * @param MerchantDetailsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function regiasterMerchantCompanyDetails(MerchantDetailsRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->createdResponse(
            'Merchant company details created successfully',
            $this->registerServices->storeMerchantInSession($request->validated())
        );
    }

    /**
     * @param MerchantPersonalDetailsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerPersonalDetails(MerchantPersonalDetailsRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->createdResponse(
            'Merchant company details created successfully',
            $this->registerServices->storeMerchantInSession($request->validated())
        );
    }

    public function verifyOtpToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:4',
        ]);
        if ($validator->fails()) {
            return $this->formValidationErrorAlert('Validation error', Arr::flatten($validator->errors()->toArray()));
        }

        // validate otp
        $token = $this->registerServices->validateMerchantOtp($request);
        if (is_null($token)) {
            return $this->notFoundResponse("Token not found");
        }

        // Create a merchant
        $this->registerServices->storeMerchantDetails();
        return $this->createdResponse("Otp inputted successfully");
    }
}
