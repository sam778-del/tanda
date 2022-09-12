<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
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
        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin')) {
            return $next($request);
        }
        abort(403, 'You do not have the required permission(s) to access this resource!');
    }
}
