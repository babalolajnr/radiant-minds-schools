<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Checks if User Is Active And Verified
 */
class CheckUserIsActiveAndVerified
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
        if (Auth::guard('web')->check()) {
            if (!$request->user()->isActive() || !$request->user()->isVerified()) {
                return response()->view('deactivated');
            }
        } elseif (Auth::guard('teacher')->check()) {
            if (!$request->user('teacher')->isActive()) {
                return response()->view('deactivated');
            }
        }

        return $next($request);
    }
}
