<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return response(200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'unique:subjects']
        ]);

        Subject::create($request->all());
        return response(200);
    }

    public function edit($id)
    {

        $subject = Subject::findOrFail($id);
        return response(200);
    }

    public function update($id, Request $request)
    {
        $subject = Subject::findOrFail($id);
        $subject->update($request->all());
        return response(200);
    }

    public function destroy($id, Subject $subject)
    {
        $this->authorize('delete', $subject);
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return response(200);
    }
}
