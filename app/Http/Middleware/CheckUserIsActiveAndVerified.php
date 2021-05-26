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
        if (auth('web')->check()) {
            if (!$request->user('web')->isActive() || !$request->user('web')->isVerified()) {
                return redirect('deactivated');
            }
        } elseif (auth('teacher')->check()) {
            if (!$request->user('teacher')->isActive()) {
                return redirect('deactivated');
            }
        }

        return $next($request);
    }
}
