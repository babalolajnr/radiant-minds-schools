<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuardianController extends Controller
{
    private function findGuardian($guardian)
    {
        $guardian = Guardian::where('phone', $guardian);
        if (!$guardian->exists()) {
            abort(404);
        }

        return $guardian;
    }

    public function edit($guardian)
    {
        $guardian = $this->findGuardian($guardian)->first();
        return view('editGuardian', compact(['guardian']));
    }

    public function update($guardian, Request $request)
    {

        $guardian = $this->findGuardian($guardian)->first();

        $validatedData = $request->validate([
            'title' => ['required', 'max:30', 'string'],
            'first_name' => ['required', 'max:30', 'string'],
            'last_name' => ['required', 'max:30', 'string'],
            'email' => ['required', 'string', 'email:rfc,dns', Rule::unique('guardians')->ignore($guardian)],
            'phone' => ['required', 'string', 'between:10,15', Rule::unique('guardians')->ignore($guardian)],
            'occupation' => ['required', 'string'],
            'address' => ['required']
        ]);

        $guardian = $guardian->update($validatedData);

        $guardian = Guardian::where('phone', $request->phone)->first();

        return redirect('/edit/guardian/' . $guardian->phone)->with('success', 'Guardian updated!');
    }
}
