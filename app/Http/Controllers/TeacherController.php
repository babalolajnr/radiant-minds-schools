<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    private function validationFields($teacher = null)
    {
        $validationFields  = [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', Rule::unique('teachers')->ignore($teacher), 'email:rfc,dns'],
            'phone' => ['required', 'string', Rule::unique('teachers')->ignore($teacher), 'max:15', 'min:10'],
            'date_of_birth' => ['required', 'date', 'before:' . now()],
        ];
        return $validationFields;
    }

    private function generateFullNameSlug($firstName, $lastName)
    {
        $fullname = $firstName . ' ' . $lastName . ' ' . Str::random(5);
        $slug = Str::of($fullname)->slug('-');

        return $slug;
    }

    public function index()
    {
        $teachers = Teacher::all();
        return view('teachers', compact('teachers'));
    }

    public function create()
    {
        return view('createTeacher');
    }

    public function store(Request $request)
    {
        $validationFields = $this->validationFields() +  ['sex' => ['required', 'string']];
        $validatedData = $request->validate($validationFields);

        $slug = $this->generateFullNameSlug($validatedData['first_name'], $validatedData['last_name']);

        $data = array_merge($validatedData, ['slug' => $slug]);

        Teacher::create($data);

        return redirect()->route('teacher.index')->with('success', 'Teacher Created');
    }

    public function show(Teacher $teacher)
    {
        return view('showTeacher', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        return view('editTeacher', compact('teacher'));
    }

    public function update(Teacher $teacher, Request $request)
    {
        $validationFields = $this->validationFields($teacher);
        $validatedData = $request->validate($validationFields);

        //check if either the first or last name has changed to generate a new slug
        if ($teacher->first_name != $validatedData['first_name'] || $teacher->last_name != $validatedData['last_name']) {
            $slug = $this->generateFullNameSlug($validatedData['first_name'], $validatedData['last_name']);
            $data = array_merge($validatedData, ['slug' => $slug]);
        } else {
            $data = $validatedData;
        }

        $teacher->update($data);

        return redirect()->route('teacher.edit', ['teacher' => $teacher])->with('success', 'Teacher Updated!');
    }

    public function activate(Teacher $teacher)
    {
        $teacher->is_active = true;
        $teacher->save();

        return redirect()->back()->with('success', 'Teacher Activated!');
    }

    public function deactivate(Teacher $teacher)
    {
        $teacher->is_active = false;
        $teacher->save();

        return redirect()->back()->with('success', 'Teacher Deactivated!');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->back()->with('success', 'Teacher Deleted!');
    }
}
