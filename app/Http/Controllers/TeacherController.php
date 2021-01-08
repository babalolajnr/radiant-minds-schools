<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
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

        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'unique:teachers', 'email:rfc,dns'],
            'phone' => ['required', 'string', 'unique:teachers', 'max:15', 'min:10'],
            'date_of_birth' => ['required', 'date']
        ]);

        $fullname = $request->first_name . ' ' . $request->last_name . ' ' . Str::random(5);
        $slug = Str::of($fullname)->slug('-');

        Teacher::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'slug' => $slug,
            'status' => 'inactive'
        ]);

        return response(200);
    }

    public function show($slug)
    {
        $teacher = Teacher::where('slug', $slug);

        if ($teacher->exists()) {
            return response(200);
        } else {
            abort(404);
        }
    }

    public function edit($slug, Teacher $teacher)
    {
        $this->authorize('update', $teacher);

        $teacher = Teacher::where('slug', $slug);

        if ($teacher->exists()) {
            return response(200);
        } else {
            abort(404);
        }
    }

    public function update($slug, Teacher $teacher, Request $request)
    {
        $this->authorize('update', $teacher);

        $teacher = Teacher::where('slug', $slug);

        if ($teacher->exists()) {

            $this->validate($request, [
                'first_name' => ['required', 'string', 'max:30'],
                'last_name' => ['required', 'string', 'max:30'],
                'email' => ['required', 'string', Rule::unique('teachers')->ignore($teacher->first()), 'email:rfc,dns'],
                'phone' => ['required', 'string', Rule::unique('teachers')->ignore($teacher->first()), 'max:15', 'min:10'],
                'date_of_birth' => ['required', 'date']
            ]);

            $fullname = $request->first_name . ' ' . $request->last_name . ' ' . Str::random(5);
            $slug = Str::of($fullname)->slug('-');

            $teacher->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'slug' => $slug,
            ]);

            return response(200);
        } else {
            abort(404);
        }
    }

    public function destroy($id, Teacher $teacher)
    {
        $this->authorize('delete', $teacher);

        $teacher = Teacher::findOrFail($id);

        $teacher->delete();

        return response(200);
    }

    public function forceDelete($id, Teacher $teacher){
        $this->authorize('forceDelete', $teacher);

        $teacher = Teacher::findOrFail($id);

        $teacher->forceDelete();

        return response(200);
    }
}
