<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Web\LoginRequest;
use Illuminate\Support\Arr;
use App\Traits\ApiResponse;
use App\Models\Employer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request)
    {
        return view('frontend.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $input = $request->validated();
        $credentials = $this->credentials($input);
        $token = Auth::guard('employer')->attempt($credentials);
        
        if (!$token) {
            return redirect()->back()->with('error', __('Invalid credentials'));
        }

        $user = Employer::query()
            ->orWhere('email', $input['email'])
            ->first();

        if ($user->status == Employer::DISABLE) {
            return redirect()->back()->with('error', __('Account is still disabled'));
        }
        return redirect()->intended('/overview');
    }

    public function credentials(array $input): array
    {
        $email = $input["email"];
        $password = $input["password"];
        return [
            'email' => $email,
            'password' => $password
        ];
    }

    public function logout()
    {
        Auth::guard('employer')->logout();
        return redirect()->route('login.get');
    }
}
