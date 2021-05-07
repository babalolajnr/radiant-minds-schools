<?php

namespace App\Http\Controllers;

use App\Models\ADType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ADTypeController extends Controller
{
    private function validateADType($request, $adType = null)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', Rule::unique('a_d_types', 'name')->ignore($adType)]
        ]);

        return $validatedData;
    }
    public function index()
    {
        $adTypes = ADType::all();
        return view('adTypes', compact('adTypes'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateADType($request);

        $slug = Str::of($validatedData['name'])->slug('-');
        $slug = ['slug' => $slug];
        $data = array_merge($validatedData, $slug);

        ADType::create($data);
        return back()->with('success', 'Affective Domain created!');
    }

    public function edit(ADType $adType)
    {
        return view('editADType', compact('adType'));
    }

    public function update(ADType $adType, Request $request)
    {
        $validatedData = $this->validateADType($request, $adType);
        $slug = Str::of($validatedData['name'])->slug('-');
        $slug = ['slug' => $slug];
        $data = array_merge($validatedData, $slug);

        $adType->update($data);
        return redirect()->route('ad-type.index')->with('success', 'Affective domain type updated');
    }

    public function destroy(ADType $adType)
    {
        
        try {
            $adType->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                //SQLSTATE[23000]: Integrity constraint violation
                return back()->with('error', 'Affective Domain Type cannot be deleted because some resources are dependent on it!');
            }
        }
        return back()->with('success', 'Deleted!');
    }
}
