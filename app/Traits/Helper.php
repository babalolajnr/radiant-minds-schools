<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait Helper
{
    
    /**
     * Get Authenticated user
     *
     * @return void
     */
    public function getAuthUser()
    {
        if (Auth::guard('teacher')->check()) {
            return Auth::guard('teacher')->user();
        }

        return Auth::guard('web')->user();
    }
}
