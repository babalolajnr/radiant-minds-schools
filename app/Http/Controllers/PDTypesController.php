<?php

namespace App\Http\Controllers;

use App\Models\PDType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PDTypesController extends Controller
{
    private function validatePDType($request, $pdType = null)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', Rule::unique('p_d_types', 'name')->ignore($pdType)]
        ]);

        return $validatedData;
    }
    public function index()
    {
        $pdTypes = PDType::all();
        return view('pdTypes', compact('pdTypes'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validatePDType($request);

        $slug = Str::of($validatedData['name'])->slug('-');
        $slug = ['slug' => $slug];
        $data = array_merge($validatedData, $slug);

        PDType::create($data);
        return back()->with('success', 'Pyschomotor Domain created!');
    }

    public function edit(PDType $pdType)
    {
        return view('editPDType', compact('pdType'));
    }

    public function update(PDType $pdType, Request $request)
    {
        $validatedData = $this->validatePDType($request, $pdType);
        $slug = Str::of($validatedData['name'])->slug('-');
        $slug = ['slug' => $slug];
        $data = array_merge($validatedData, $slug);

        $pdType->update($data);
        return redirect()->route('pd-type.index')->with('success', 'Pychomotor domain type updated');
    }

    public function destroy(PDType $pdType)
    {
        
        try {
            $pdType->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                //SQLSTATE[23000]: Integrity constraint violation
                return back()->with('error', 'Pychodomain Type can not be deleted because some resources are dependent on it!');
            }
        }
        return back()->with('success', 'Deleted!');
    }
}
