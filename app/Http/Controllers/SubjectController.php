<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        return response(200);
    }

    public function store(Request $request)
    {
        $validatedData =  $request->validate([
            'name' => ['required', 'string', 'unique:subjects']
        ]);

        $slug = Str::of($validatedData['name'])->slug('-');
        $slug = ['slug' => $slug];
        $data = $validatedData + $slug;
        Subject::create($data);
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
