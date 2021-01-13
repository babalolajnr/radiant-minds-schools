<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::all();
        return response(200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'unique:terms']
        ]);

        Term::create($request->all());
        return response(200);
    }

    public function edit($id)
    {

        $term = Term::findOrFail($id);
        return response(200);
    }

    public function update($id, Request $request)
    {
        $term = Term::findOrFail($id);
        $term->update($request->all());
        return response(200);
    }

    public function destroy($id, Term $term)
    {
        $this->authorize('delete', $term);
        $term = Term::findOrFail($id);
        $term->delete();
        return response(200);
    }
}
