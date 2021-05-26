<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use  Intervention\Image\Facades\Image;

class TeacherController extends Controller
{    
    /**
     * get validation fiels
     *
     * @param  mixed $teacher
     * @return array
     */
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

    /**
     * Generate full name slug
     *
     * @param  string $firstName
     * @param  string $lastName
     * @return \Illuminate\Support\Stringable
     */
    private function generateFullNameSlug($firstName, $lastName)
    {
        $fullname = $firstName . ' ' . $lastName . ' ' . Str::random(5);
        $slug = Str::of($fullname)->slug('-');

        return $slug;
    }
    
    /**
     * get teachers view
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $teachers = Teacher::all();
        return view('teachers', compact('teachers'));
    }
    
    /**
     * get create view
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('createTeacher');
    }
    
    /**
     * store new teacher
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validationFields = $this->validationFields() +  ['sex' => ['required', 'string']];
        $validatedData = $request->validate($validationFields);

        $slug = $this->generateFullNameSlug($validatedData['first_name'], $validatedData['last_name']);

        $data = array_merge($validatedData, ['slug' => $slug]);

        Teacher::create($data);

        return redirect()->route('teacher.index')->with('success', 'Teacher Created');
    }
    
    /**
     * show teacher
     *
     * @param  Teacher $teacher
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Teacher $teacher)
    {
        return view('showTeacher', compact('teacher'));
    }
    
    /**
     * get edit teacher view.
     *
     * @param  Teacher $teacher
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Teacher $teacher)
    {
        return view('editTeacher', compact('teacher'));
    }
    
    /**
     * update
     *
     * @param  mixed $teacher
     * @param  mixed $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
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
    
    /**
     * activate teacher
     *
     * @param  Teacher $teacher
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function activate(Teacher $teacher)
    {
        $teacher->is_active = true;
        $teacher->save();

        return redirect()->back()->with('success', 'Teacher Activated!');
    }
    
    /**
     * deactivate teacher
     *
     * @param  Teacher $teacher
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function deactivate(Teacher $teacher)
    {
        $teacher->is_active = false;
        $teacher->save();

        return redirect()->back()->with('success', 'Teacher Deactivated!');
    }

    /**
     * delete teacher
     *
     * @param  Teacher $teacher
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->back()->with('success', 'Teacher Deleted!');
    }

    /**
     * store teacher Signature
     *
     * @param  Teacher $teacher
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSignature(Teacher $teacher, Request $request)
    {
        $this->authorize('storeSignature', $teacher);

        $request->validate([
            'signature' => ['required', 'image', 'unique:teachers,signature,except,id', 'mimes:jpg', 'max:1000']
        ]);

        //create name from first and last name
        $signatureName = $teacher->first_name . $teacher->last_name . '.' . $request->signature->extension();
        $path = $request->file('signature')->storeAs('public/teachers/signatures', $signatureName);
        Image::make($request->signature->getRealPath())->fit(400, 400)->save(storage_path('app/' . $path));

        //update signature in the database
        $filePath = 'storage/teachers/signatures/' . $signatureName;
        $teacher->signature = $filePath;
        $teacher->save();

        return back()->with('success', 'Signature uploaded successfully');
    }
}
