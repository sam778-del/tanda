<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class SuperAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('employer')->check()) {
            return $next($request);
        }
    }
}