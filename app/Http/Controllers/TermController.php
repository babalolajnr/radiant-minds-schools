<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::all();
        return view('terms', compact('terms'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateTerm($request);
        $slug = ['slug' =>  Str::of($validatedData['name'])->slug('-')];
        Term::create($validatedData + $slug);
        return redirect()->back()->with('success', 'Term created!');
    }

    public function edit(Term $term)
    {
        return view('editTerm', compact('term'));
    }

    public function update(Term $term, Request $request)
    {
        $validatedData = $this->validateTerm($request, $term);
        $slug = ['slug' =>  Str::of($validatedData['name'])->slug('-')];
        $term->update($validatedData + $slug);
        return redirect()->route('term.index')->with('success', 'Term updated!');
    }

    public function destroy(Term $term)
    {
        // $this->authorize('delete', $term);

        try {
            $term->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                //SQLSTATE[23000]: Integrity constraint violation
                return back()->with('error', 'Term can not be deleted because some resources are dependent on it!');
            }
        }

        return redirect()->back()->with('success', 'Term deleted!');
    }

    private function validateTerm($request, $term = null)
    {
        $messages = [
            'name.unique' => 'Term Exists'
        ];

        $validatedData = $request->validate([
            'name' => ['required', 'string', Rule::unique('terms')->ignore($term)]
        ], $messages);

        return $validatedData;
    }
}
