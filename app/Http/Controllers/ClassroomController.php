<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
        $classrooms = Classroom::all()->sortBy('rank');
        return view('classrooms', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $maxRank = Classroom::max('rank');
        $rank = ['rank' => $maxRank + 1];
        $validatedData = $this->classroomValidation($request);
        $slug = ['slug' => Str::of($validatedData['name'])->slug('-')];
        $data = $rank + $slug + $validatedData;
        Classroom::create($data);
        return back()->with('success', 'Classroom Created!');
    }

    public function edit(Classroom $classroom)
    {
        return view('editClassroom', compact('classroom'));
    }

    public function update(Classroom $classroom, Request $request)
    {
        $classrooms = Classroom::all();
        $maxRank = $classrooms->max('rank');
        $currentRank = $classroom->rank;

        $validatedData = $request->validate([
            'name' => ['required', 'string', Rule::unique('classrooms')->ignore($classroom)],
            'rank' => ['required', 'numeric', 'min:1', 'max:' . $maxRank]
        ]);

        /** 
         * get row where rank is equal to the posted rank and if it exists
         * set the rank of the row to 0, then update the classroom that need to be updated
         * to avoid unique constraint error. Set the row whose rank was set to 0
         * to the previous rank of the updated classroom model.
         */
        $rank = $validatedData['rank'];
        $row = Classroom::where('rank', $rank)->first();
        $slug = ['slug' => Str::of($validatedData['name'])->slug('-')];

        //if row exists
        if (!is_null($row)) {
            $row->rank = 0;
            $row->save();
            $classroom->update($validatedData + $slug);
            $row->rank = $currentRank;
            $row->save();
        } else {
            $classroom->update($validatedData + $slug);
        }

        return redirect('/classrooms')->with('success', 'Classroom Updated!');
    }

    public function show(Classroom $classroom)
    {
        $students = $classroom->students->all();
        $academicSessions = AcademicSession::all();
        $terms = Term::all();
        $currentAcademicSession = AcademicSession::currentAcademicSession();

        if (is_null($currentAcademicSession)) {
            return back()->with('error', 'Current Academic session is not set!');
        }

        $subjects = $classroom->subjects()->where('academic_session_id', $currentAcademicSession->id)->get();

        return view('showClassroom', compact('students', 'classroom', 'academicSessions', 'terms', 'subjects'));
    }

    public function destroy(Classroom $classroom)
    {
        try {
            $classroom->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                //SQLSTATE[23000]: Integrity constraint violation
                return back()->with('error', 'Classroom can not be deleted because some resources are dependent on it!');
            }
        }

        /**
         * update the rank of the other classes
         * 
         * get all the classes sorted by their current rank
         * and then loop through them to update their ranks
         * while incrementing the rank
         */
        $classrooms = Classroom::all()->sortBy('rank');
        $rank = 1;
        foreach ($classrooms as $classroom) {
            $classroom->update(['rank' => $rank]);
            $rank++;
        }

        return back()->with('success', 'Classroom Deleted!');
    }

    /**
     * This method return the setSubjects view
     */
    public function setSubjects(Classroom $classroom)
    {
        $subjects = Subject::all();
        $relations = [];

        //NOTE: subjects can only be set for the current academic session
        $currentAcademicSession = AcademicSession::currentAcademicSession();

        //loop subjects and get the ones that are related to the classroom
        foreach ($subjects as $subject) {
            $relation = $subject->classrooms()->where('classroom_id', $classroom->id)->where('academic_session_id', $currentAcademicSession->id)->exists();
            $relations = array_merge($relations, [$subject->name => $relation]);
        }

        //set array as collection for it to be showable in the view
        $relations = collect($relations);

        return view('setSubjects', compact('relations', 'classroom'));
    }

    /**
     * This method stores or update subjects for the given classroom
     */
    public function updateSubjects(Classroom $classroom, Request $request)
    {
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

    public function assignTeacher(Classroom $classroom, $teacherSlug)
    {
        $teacher = Teacher::where('slug', $teacherSlug)->firstOrFail();
        $classroom->teacher_id = $teacher->id;
        $classroom->save();

        return back()->with('success', "{$teacher->first_name} {$teacher->last_name} assigned to {$classroom->name}");
    }
}
