<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::all();
        return response(200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'unique:classrooms']
        ]);

        Classroom::create($request->all());
        return response(200);

    }

    public function edit($id){
        
        $classroom = Classroom::findOrFail($id);
        return response(200);
    }

    public function update($id, Request $request)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->update($request->all());
        return response(200);
    }
}
