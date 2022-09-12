<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\RegisterRequest;
use App\Jobs\Web\EmailConfirmationJob;
use Illuminate\Auth\Events\Registered;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use App\Services\BankService;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Employer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Socialite;

class RegisterController extends Controller
{
    use ApiResponse;

    private $bankService;
    private $planService;

    public function __construct()
    {
        $this->bankService = resolve(BankService::class);
        $this->planService = resolve(PlanService::class);
    }

    public function __invoke(Request $request)
    {
        return $request->user();
        // return view('frontend.auth.register');
    }

    public function registerEmployer(RegisterRequest $request)
    {
        $employer = $this->createEmployer($request->validated());

        if(Auth::guard('employer')->login($employer))
        {
            $this->sendEmailJob($employer);
            $this->planService->assignFreePlan($employer->id);
            return redirect()->intended('/signuporginazationdetail');
        }else{
            return redirect()->back()->with('error', __('Unable to create user'));
        }
    }

    private function generateBvn(){
        return strtoupper(str_replace('.', '', uniqid('', true)));
    }

    public function redirect($provider)
    {
        try {
            return Socialite::driver($provider)->stateless()->redirect();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function callback($provider)
    {
        try {
            $getInfo = Socialite::driver($provider)->stateless()->user();
            $employer = $this->createUser($getInfo, $provider);

            if(! $token = Auth::guard('employer')->login($employer))
            {
                $employer->delete();
                abort(403, "Could not validate employer");
            }

            $loginToken = $this->respondWithToken($token);
            $this->sendEmailJob($employer);
            $this->planService->assignFreePlan($employer->id);
            return response()->json(
                [
                    "employer" => $employer,
                    "token" => $loginToken,
                    "response" => "Employer created successfully"
                ]
            );
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function createUser($getInfo, $provider){
        $user = Employer::where(function($query) use ($getInfo) {
            $query->where('google_id', $getInfo->id)
            ->orWhere('email', $getInfo->email);
        })->first();
        if (!$user) {
            $user = Employer::create([
               'first_name'     => $getInfo->offsetGet('given_name'),
               'last_name'      => $getInfo->offsetGet('family_name'),
               'email'    => $getInfo->email,
               'status'  => Employer::ENABLE,
               'google_id' => $getInfo->id
           ]);
         }
        return $user;
    }

    public function createEmployer(array $attribute)
    {
        return Employer::create([
            'first_name' => Arr::get($attribute, 'first_name'),
            'last_name' => Arr::get($attribute, 'last_name'),
            'email' => Arr::get($attribute, 'email'),
            'password' => Arr::get($attribute, 'password'),
            'know_us' => Arr::get($attribute, 'know_us'),
            'status'  => Employer::ENABLE
        ]);
    }

    public function sendEmailJob(Employer $user)
    {
        // event(new Registered($user));
        $user->sendEmailVerificationNotification();
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('employer')->factory()->getTTL() * 60
        ];
    }

    public function confirmationEmail(Request $request)
    {
        $user = $request->user();
        if(isset($user) && !empty($user))
        {
            return view('frontend.auth.confirmemail');
        }
        return redirect()->route('login.get')->with('error', __('User not authenticated'));
    }

    public function organizationDetail(Request $request)
    {
        $user = $request->user();
        if(isset($user) && !empty($user))
        {
            return view('frontend.auth.organizationdetails');
        }
        return redirect()->route('login.get')->with('error', __('User not authenticated'));
    }
}
