<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{

    private function studentInfo($request)
    {
        $classroom =  Classroom::where('name', $request->classroom)->first();

        return [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'sex' => $request->sex,
            'admission_no' => $request->admission_no,
            'lg' => $request->lg,
            'state' => $request->state,
            'country' => $request->country,
            'blood_group' => $request->blood_group,
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'classroom_id' => $classroom->id,
            'status' => 'active'
        ];
    }

    public function index()
    {
        $students = Student::all()->sortByDesc('created_at');
        $academicSessions = AcademicSession::all()->sortByDesc('created_at');
        return view('students', compact('students', 'academicSessions'));
    }

    public function create()
    {
        $classrooms = Classroom::pluck('name')->all();
        return view('newStudent', compact('classrooms'));
    }

    /**
     * This method works by collecting all the guardian and student info from the user and
     * making sure it's all filled out. Then it checks if the guardian's phone number is present
     * in the database. If it is then it gets the guardian's id and inserts it into the student's table
     */
    public function store(Request $request)
    {
        // $this->authorize('create', Student::class);
        $currentDate = now();
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'sex' => ['required', 'string'],
            'admission_no' => ['required', 'string', 'unique:students'],
            'lg' => ['required', 'string'],
            'state' => ['required', 'string'],
            'country' => ['required', 'string'],
            'blood_group' => ['required', 'string'],
            'date_of_birth' => ['required', 'date', 'before:' . $currentDate],
            'place_of_birth' => ['required'],
            'classroom' => ['required', 'string'],
            'guardian_title' => ['required', 'max:30', 'string'],
            'guardian_first_name' => ['required', 'max:30', 'string'],
            'guardian_last_name' => ['required', 'max:30', 'string'],
            'guardian_email' => ['required', 'string', 'email:rfc,dns'],
            'guardian_phone' => ['required', 'string', 'between:10,15'],
            'guardian_occupation' => ['required', 'string'],
            'guardian_address' => ['required']
        ]);


        $guardian = Guardian::where('phone', $request->guardian_phone)->first();

        if (is_null($guardian)) {
            $guardian = Guardian::create([
                'title' => $request->guardian_title,
                'first_name' => $request->guardian_first_name,
                'last_name' => $request->guardian_last_name,
                'email' => $request->guardian_email,
                'phone' => $request->guardian_phone,
                'occupation' => $request->guardian_occupation,
                'address' => $request->guardian_address,
            ]);
        }

        //assign guardian_id to an array and merge it with the original student info array
        $guardianID = ['guardian_id' => $guardian->id];
        $studentInfo = array_merge($this->studentInfo($request), $guardianID);

        Student::create($studentInfo);

        return redirect('/students')->with('success', 'Student Added!');
    }

    public function show($student)
    {
        $student = Student::where('admission_no', $student);
        if ($student->exists()) {
            return response(200);
        } else {
            abort(404);
        }
    }

    public function suspend($id)
    {
        $student = Student::findOrFail($id);

        $student->status = 'suspended';

        $student->save();

        return response(200);
    }

    public function activate($id)
    {
        $student = Student::findOrFail($id);

        $student->status = 'active';

        $student->save();

        return response(200);
    }

    public function deactivate($id)
    {
        $student = Student::findOrFail($id);

        $student->status = 'inactive';

        $student->save();

        return response(200);
    }

    public function edit($student)
    {
        $student = Student::where('admission_no', $student);
        if (!$student->exists()) {
            abort(404);
        }
        $student = $student->first();
        $classrooms = Classroom::pluck('name')->all();
        return view('editStudent', compact(['student', 'classrooms']));
    }

    public function update($id, Request $request)
    {
        $student = Student::findOrFail($id);
        $currentDate = now();

        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'sex' => ['required', 'string'],
            'admission_no' => ['required', 'string', Rule::unique('students')->ignore($student)],
            'lg' => ['required', 'string'],
            'state' => ['required', 'string'],
            'country' => ['required', 'string'],
            'blood_group' => ['required', 'string'],
            'date_of_birth' => ['required', 'date', 'before:' . $currentDate],
            'place_of_birth' => ['required'],
            'classroom' => ['required', 'string'],
        ]);

        $student->update($this->studentInfo($request));
        return redirect('/edit/student/' . $student->admission_no)->with('success', 'Student Updated!');
    }

    public function getSessionalResults($student, Request $request)
    {
        $student = Student::where('admission_no', $student);

        if (!$student->exists()) {
            abort(404);
        }

        $this->validate($request, [
            'academicSession' => ['required']
        ]);

        $student =  $student->first();
        $academicSession = AcademicSession::where('name', $request->academicSession)->first();
        $terms = Term::all();
        $results = [];

        foreach ($terms as $term) {
            $result = Result::where('student_id', $student->id)
                ->where('academic_session_id', $academicSession->id)
                ->where('term_id', $term->id)->get();
            $result = [$term->name => $result];
            $results = array_merge($results, $result);
        }

        return view('studentResults', compact('results'));
    }

    public function getSubjects($student)
    {
        $student = Student::where('admission_no', $student);

        if (!$student->exists()) {
            abort(404);
        }
        $student = $student->first();
        $subjects = $student->classroom->subjects()->get();

        return response(200);
    }

    public function destroy($id, Student $student)
    {
        $this->authorize('delete', $student);

        $student = Student::findOrFail($id);

        $student->delete();

        return response(200);
    }

    public function forceDelete($id, Student $student)
    {
        $this->authorize('forceDelete', $student);
        $student = Student::findOrFail($id);
        $student->forceDelete();
        return response(200);
    }
}
