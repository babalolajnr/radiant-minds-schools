<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    private function teacherValidation($request, $teacher = null){
      $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', Rule::unique('teachers')->ignore($teacher), 'email:rfc,dns'],
            'phone' => ['required', 'string', Rule::unique('teachers')->ignore($teacher), 'max:15', 'min:10'],
            'date_of_birth' => ['required', 'date']
        ]);

        return $validatedData;
    }

    private function generateFullNameSlug($firstName, $lastName){
        $fullname = $firstName . ' ' . $lastName . ' ' . Str::random(5);
        $slug = Str::of($fullname)->slug('-');

        return $slug;
    }

    public function index()
    {
    }

    public function create()
    {
        $this->authorize('create', Teacher::class);
        return response(200);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Teacher::class);

        $validatedData = $this->teacherValidation($request);

        $slug = $this->generateFullNameSlug($validatedData['first_name'], $validatedData['last_name']);

        $data = array_merge($validatedData, ['slug' => $slug, 'status' => 'active']);

        Teacher::create($data);

        return response(200);
    }

    public function show(Teacher $teacher)
    {
        return $teacher->exists() ? response(200) : abort(404);
    }

    public function edit(Teacher $teacher)
    {
        $this->authorize('update', $teacher);

        return $teacher->exists() ? response(200) : abort(404);
    }

    public function update(Teacher $teacher, Request $request)
    {
        $this->authorize('update', $teacher);

        if (!$teacher->exists()) {
            abort(404);
        }
        $validatedData = $this->teacherValidation($request);

        $slug = $this->generateFullNameSlug($validatedData['first_name'], $validatedData['last_name']);
        
        $data = array_merge($validatedData, ['slug' => $slug]);

        $teacher->update($data);

        return response(200);
    }

    public function suspend(Teacher $teacher)
    {
        $this->authorize('suspend', $teacher);

        $teacher->status = 'suspended';

        $teacher->save();

        return response(200);
    }

    public function activate(Teacher $teacher)
    {
        $this->authorize('activate', $teacher);

        $teacher->status = 'active';

        $teacher->save();

        return response(200);
    }

    public function deactivate(Teacher $teacher)
    {
        $this->authorize('deactivate', $teacher);

        $teacher->status = 'inactive';

        $teacher->save();

        return response(200);
    }

    public function destroy(Teacher $teacher)
    {
        $this->authorize('delete', $teacher);

        $teacher->delete();

        return response(200);
    }

    public function forceDelete($id, Teacher $teacher)
    {
        $this->authorize('forceDelete', $teacher);

        $teacher = Teacher::findOrFail($id);

        $teacher->forceDelete();
        return response(200);
    }

    public function restore($id, Teacher $teacher)
    {
        $this->authorize('restore', $teacher);

        $teacher = Teacher::onlyTrashed()->findOrFail($id);

        $teacher->restore();

        return response(200);
    }
}
