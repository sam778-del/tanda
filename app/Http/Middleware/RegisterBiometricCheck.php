<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RegisterBiometricCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->hasHeader('X-DEVICE-ID')) {
            abort(400, 'Device ID is required to continue.');
        }

        $mobileCredential = auth()->user()->mobileCredential;
        if (!empty($mobileCredential->public_key)) {
            abort(400, 'Sorry you have biometrics enabled already');
        }

        return $next($request);
    }
}
