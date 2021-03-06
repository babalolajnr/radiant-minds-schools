<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        Term::create($validatedData);
        return back()->with('success', 'Term created!');
    }

    public function edit($id)
    {

        $term = Term::findOrFail($id);
        return response(200);
    }

    public function update($id, Request $request)
    {
        $term = Term::findOrFail($id);
        $validatedData = $this->validateTerm($request, $term);
        $term->update($validatedData);
        return response(200);
    }

    public function destroy($id, Term $term)
    {
        $this->authorize('delete', $term);
        $term = Term::findOrFail($id);
        $term->delete();
        return response(200);
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
