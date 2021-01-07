<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        $this->autorize('create', Teacher::class);
        return response(200);
    }

    public function store(Request $request)
    {
        $this->autorize('create', Teacher::class);

        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'unique', 'email:rfc,dns'],
            'phone' => ['required', 'string', 'unique', 'max:11', 'min:11'],
            'date_of_birth' => ['required', 'date']
        ]);

        Teacher::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'status' => 'inactive'
        ]);

        return response(200);
    }

    public function show()
    {
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
