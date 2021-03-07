<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    private function validateSubject($request, $subject = null)
    {
        $messages = [
            'name.unique' => 'Subject Exists'
        ];

        $validatedData =  $request->validate([
            'name' => ['required', 'string', Rule::unique('subjects')->ignore($subject)]
        ], $messages);

        return $validatedData;
    }

    public function index()
    {
        $subjects = Subject::all();
        return view('subjects', compact('subjects'));
    }

    public function store(Request $request)
    {

        $validatedData = $this->validateSubject($request);
        $slug = Str::of($validatedData['name'])->slug('-');
        $slug = ['slug' => $slug];
        $data = $validatedData + $slug;
        Subject::create($data);
        return back()->with('success', 'Subject Added!');
    }

    public function edit($id)
    {

        $subject = Subject::findOrFail($id);
        return view('editSubject', compact('subject'));
    }

    public function update($id, Request $request)
    {
        $subject = Subject::findOrFail($id);

        $validatedData = $this->validateSubject($request);
        $slug = Str::of($validatedData['name'])->slug('-');
        $slug = ['slug' => $slug];
        $data = $validatedData + $slug;

        $subject->update($data);
        return redirect('/subjects')->with('success', 'Subject Updated!');
    }

    public function destroy($id, Subject $subject)
    {
        $this->authorize('delete', $subject);
        $subject = Subject::findOrFail($id);

        try {
            $subject->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                //SQLSTATE[23000]: Integrity constraint violation
                return back()->with('error', 'Subject can not be deleted because some resources are dependent on it!');
            }
        }
        return back()->with('success', 'Subject deleted!');
    }
}
