<?php

namespace App\Utilities;

use App\Models\User;

class TestUtilities {

    /**
     * @return mixed
     * get an admin user 
     */
    public static function getAdminUser()
    {
        $user = User::where('user_type', 'admin')->first();
        return $user;
    }
}