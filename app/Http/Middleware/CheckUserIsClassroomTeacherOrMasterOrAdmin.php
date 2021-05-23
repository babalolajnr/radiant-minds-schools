<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserIsClassroomTeacherOrMasterOrAdmin
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
        if (Auth::guard('teacher')->check()) {

            //check if authenticated teacher is the classteacher of the classroom to be viewed
            if (!$request->user()->classroom->id == $request->route('classroom')->id) {
                abort(403);
            }
        }
        
        return $next($request);
    }
}
