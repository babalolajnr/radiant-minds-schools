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

    public function edit($slug)
    {
        $pdType = PDType::where('slug', $slug)->first();
        return view('editPDType', compact('pdType'));
    }

    public function update($id, Request $request)
    {
        $pdType = PDType::findOrFail($id);
        $validatedData = $this->validatePDType($request, $pdType);
        $slug = Str::of($validatedData['name'])->slug('-');
        $slug = ['slug' => $slug];
        $data = array_merge($validatedData, $slug);

        $pdType->update($data);
        return redirect('/pdTypes')->with('success', 'Pychomotor domain type updated');
    }

    public function destroy($id)
    {
        $pdType = PDType::findOrFail($id);
        $relations = $pdType->pds()->exists();

        if ($relations) {
            return back()->with('error', 'You are not allowed to delete ' . $pdType->name . ' because it has related models');
        }
        $pdType->delete();
        return back()->with('success', 'Deleted!');
    }
}
