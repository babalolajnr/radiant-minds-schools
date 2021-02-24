<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClassroomController extends Controller
{
    private function classroomValidation($request)
    {

        $messages = [
            'name.unique' => 'Classroom Exists'
        ];

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'unique:classrooms']
        ], $messages);

        return $validatedData;
    }

    public function index()
    {
        $classrooms = Classroom::all();
        return view('classrooms', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $maxRank = Classroom::max('rank');
        $rank = ['rank' => $maxRank + 1];
        $data = array_merge($rank, $this->classroomValidation($request));
        Classroom::create($data);
        return back()->with('success', 'Classroom Created!');
    }

    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);
        return view('editClassroom', compact('classroom'));
    }

    public function update($id, Request $request)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->update($this->classroomValidation($request));
        return redirect('/classrooms')->with('success', 'Classroom Updated!');
    }

    public function show($id)
    {
        $classroom = Classroom::findOrFail($id);
        $students = $classroom->students->all();
        $academicSessions = AcademicSession::all();
        $terms = Term::all();
        $currentAcademicSession = AcademicSession::currentAcademicSession();
        $subjects = $classroom->subjects()->where('academic_session_id', $currentAcademicSession->id)->get();

        return view('showClassroom', compact('students', 'classroom', 'academicSessions', 'terms', 'subjects'));
    }

    public function destroy($id, Classroom $classroom)
    {
        $this->authorize('delete', $classroom);
        $classroom = Classroom::findOrFail($id);
        $student = Student::where('classroom_id', $classroom->id);
        $classroomSubject = $classroom->subjects()->first();

        //test for constraints
        if ($student->exists()) {
            return back()->with('error', 'You cannot delete a classroom that has students!');
        } else if (!is_null($classroomSubject)) {
            return back()->with('error', 'You cannot delete a classroom that has subjects assigned');
        }

        $classroom->delete();
        return back()->with('success', 'Classroom Deleted!');
    }

    public function setSubjects($id)
    {
        $classroom = Classroom::findOrFail($id);

        $subjects = Subject::all();
        $relations = [];
        $currentAcademicSession = AcademicSession::currentAcademicSession();

        //loop subjects and get the ones that are related to the classroom
        foreach ($subjects as $subject) {
            $relation = $subject->classrooms()->where('classroom_id', $id)->where('academic_session_id', $currentAcademicSession->id)->exists();
            $relations = array_merge($relations, [$subject->name => $relation]);
        }

        //set array as collection for it to be showable in the view
        $relations = collect($relations);

        return view('setSubjects', compact('relations', 'classroom'));
    }

    public function updateSubjects($id, Request $request)
    {
        $classroom = Classroom::findOrFail($id);

        $this->validate($request, [
            'subjects' => ['required']
        ]);

        $subjects = $request->subjects;
        $subjectIds = [];
        $currentAcademicSession = AcademicSession::currentAcademicSession();
        $academicSessions = [];

        foreach ($subjects as $subject) {
            $subjectId = Subject::where('name', $subject)->first()->id;
            array_push($subjectIds, $subjectId);
            array_push($academicSessions, ['academic_session_id' => $currentAcademicSession->id]);
        }

        //combine the two arrays to form an associative array in order to sync the subjects and academic session
        $data = array_combine($subjectIds, $academicSessions);
        //insert all subjectIds to the related class on the pivot table
        $classroom->subjects()->sync($data);


        return back()->with('success', 'Subjects set successfully');
    }
}
