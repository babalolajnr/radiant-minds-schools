<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::all();
        return view('classrooms', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.unique' => 'Classroom Exists'
        ];

        $this->validate($request, [
            'name' => ['required', 'string', 'unique:classrooms']
        ], $messages);

        Classroom::create($request->all());
        return back()->with('success', 'Classroom Created!');
    }

    public function edit($id)
    {

        $classroom = Classroom::findOrFail($id);
        return response(200);
    }

    public function update($id, Request $request)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->update($request->all());
        return response(200);
    }

    public function destroy($id, Classroom $classroom)
    {
        $this->authorize('delete', $classroom);
        $classroom = Classroom::findOrFail($id);
        $student = Student::where('classroom_id', $classroom->id);
        $classroomSubject = $classroom->subjects()->first();

        //test for constraints
        if($student->exists()){
            return back()->with('error', 'You cannot delete a classroom that has students!');
        }else if(!is_null($classroomSubject)){
            return back()->with('error', 'You cannot delete a classroom that has subjects assigned');
        }
        
        $classroom->delete();
        return back()->with('success', 'Classroom Deleted!');
    }

    public function editSubjects($id)
    {
        $classroom = Classroom::findOrFail($id);

        $subjects = $classroom->subjects()->all();
    }

    public function updateSubjects($id, Request $request)
    {
        $classroom = Classroom::findOrFail($id);

        $this->validate($request, [
            'subjects' => ['required']
        ]);

        $subjects = $request->subjects;

        foreach ($subjects as $subject){
            
            $checkUniqueness = $classroom->subjects()->where('name', $subject)->first();
            
            if(!is_null($checkUniqueness)){
                throw ValidationException::withMessages(['subjects' => $subject . ' is already registered']);
            }

            $subjectID = Subject::where('name', $subject)->first()->id;

            $classroom->subjects()->attach($subjectID);
        }
    }
}
