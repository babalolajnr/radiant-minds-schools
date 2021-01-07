<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            'phone' => ['required', 'string', 'unique:teachers', 'max:11', 'min:11'],
            'date_of_birth' => ['required', 'date']
        ]);

        $fullname = $request->first_name . ' ' . $request->last_name;
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
        // dd($slug);
        $teacher = Teacher::where('slug', $slug);
        // dd($teacher->exists());
        if ($teacher->exists()) {
            return response(200);
        }else {
            abort(404);
        }
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }
}
