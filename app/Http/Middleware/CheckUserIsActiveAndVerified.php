<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        if (auth()->user()) {
            if (!auth()->user()->isActive() || !auth()->user()->isVerified()) {
                return response()->view('deactivated');
            }
        } elseif (auth('teacher')->user()) {
            if (!auth('teacher')->user()->isActive()) {
                return response()->view('deactivated');
            }
        }
        
        return $next($request);
    }
}
