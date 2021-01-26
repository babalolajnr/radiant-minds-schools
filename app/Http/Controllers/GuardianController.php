<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    public function edit($guardian)
    {
        $guardian = Guardian::where('phone', $guardian);
        if (!$guardian->exists()) {
            abort(404);
        } 

        $guardian = $guardian->first();
        return view('editGuardian', compact(['guardian']));
    }
}
