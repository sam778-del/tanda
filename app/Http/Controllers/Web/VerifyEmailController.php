<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\Employer;
use Carbon\Carbon;

class VerifyEmailController extends Controller
{
    private $keyResolver;

    public function __construct()
    {
        $this->keyResolver = function () {
            return App::make('config')->get('app.key');
        };
    }

    public function __invoke(Request $request): JsonResponse
    {
        // if (!$this->hasValidSignature($request)) {
        //     return response()->json("Invalid/Expired url provided.", 403);
        // }

        $user = Employer::find($request->id);

        if(!$user)
        {
            abort(404, "User does not exists");
        }

        if ($user->hasVerifiedEmail()) {
            return $this->successResponse("Email Already Verified");
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->successResponse("Email Verified");
    }

    public function resendVerifcation(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return $this->successResponse("Verification link sent succesfully");
    }

    /**
     * Determine if the given request has a valid signature.
     * copied and modified from
     * vendor/laravel/framework/src/Illuminate/Routing/UrlGenerator.php:363
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $absolute
     * @return bool
     */
    public function hasValidSignature(Request $request, $absolute = true)
    {
        $url = $absolute ? $request->url() : '/'.$request->path();

        // THE FIX:
        $url = str_replace("http://","https://", $url);

        $original = rtrim($url.'?'.Arr::query(
                Arr::except($request->query(), 'signature')
            ), '?');

        $expires = $request->query('expires');

        $signature = hash_hmac('sha256', $original, call_user_func($this->keyResolver));

        return  hash_equals($signature, (string) $request->query('signature', '')) &&
            ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }
}
