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

    private function studentInfo($validatedData)
    {
        $classroom =  Classroom::where('name', $validatedData['classroom'])->first();

        return [
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'sex' => $validatedData['sex'],
            'admission_no' => $validatedData['admission_no'],
            'lg' => $validatedData['lg'],
            'state' => $validatedData['state'],
            'country' => $validatedData['country'],
            'blood_group' => $validatedData['blood_group'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'place_of_birth' => $validatedData['place_of_birth'],
            'classroom_id' => $classroom->id,
            'status' => 'active'
        ];
    }

    private function studentValidationRules($student = null)
    {
        return [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'sex' => ['required', 'string'],
            'admission_no' => ['required', 'string', Rule::unique('students')->ignore($student)],
            'lg' => ['required', 'string'],
            'state' => ['required', 'string'],
            'country' => ['required', 'string'],
            'blood_group' => ['required', 'string'],
            'date_of_birth' => ['required', 'date', 'before:' . now()],
            'place_of_birth' => ['required'],
            'classroom' => ['required', 'string'],
        ];
    }

    public function index()
    {
        $students = Student::all()->sortByDesc('created_at');
        $academicSessions = AcademicSession::all()->sortByDesc('created_at');
        $terms = Term::all()->sortByDesc('created_at');
        return view('students', compact('students', 'academicSessions', 'terms'));
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
       
        $guardianValidationRules = [
            'guardian_title' => ['required', 'max:30', 'string'],
            'guardian_first_name' => ['required', 'max:30', 'string'],
            'guardian_last_name' => ['required', 'max:30', 'string'],
            'guardian_email' => ['required', 'string', 'email:rfc,dns'],
            'guardian_phone' => ['required', 'string', 'between:10,15'],
            'guardian_occupation' => ['required', 'string'],
            'guardian_address' => ['required']
        ];

        //merge guardian and student validation rules
        $data = array_merge($guardianValidationRules, $this->studentValidationRules());
        $validatedData = $request->validate($data);

        $guardian = Guardian::where('phone', $validatedData['guardian_phone'])->first();

        //if guardian does not exist create new guardian
        if (is_null($guardian)) {
            $guardian = Guardian::create([
                'title' => $validatedData['guardian_title'],
                'first_name' => $validatedData['guardian_first_name'],
                'last_name' => $validatedData['guardian_last_name'],
                'email' => $validatedData['guardian_email'],
                'phone' => $validatedData['guardian_phone'],
                'occupation' => $validatedData['guardian_occupation'],
                'address' => $validatedData['guardian_address'],
            ]);
        }

        //merge guardian id with student info
        $studentInfo = array_merge($this->studentInfo($validatedData), ['guardian_id' => $guardian->id]);

        Student::create($studentInfo);

        return redirect('/students')->with('success', 'Student Added!');
    }

    public function show($student)
    {
        $student = Student::where('admission_no', $student);
        return $student->exists() ? response(200) : abort(404);
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
        $student = Student::findStudent($student);
        $student = $student->first();
        $classrooms = Classroom::pluck('name')->all();
        return view('editStudent', compact(['student', 'classrooms']));
    }

    public function update($id, Request $request)
    {
        $student = Student::findOrFail($id);

        $validatedData = $request->validate($this->studentValidationRules($student));

        $student->update($this->studentInfo($validatedData));
        return redirect('/edit/student/' . $student->admission_no)->with('success', 'Student Updated!');
    }

    public function getSessionalResults($student, Request $request)
    {
        $student = Student::findStudent($student);

        $this->validate($request, [
            'academicSession' => ['required', 'exists:academic_sessions,name'],
        ]);

        $student =  $student->first();
        $academicSession = AcademicSession::where('name', $request->academicSession)->first();
        $terms = Term::all();
        $results = [];
        $maxScores = [];
        $minScores = [];
        $averageScores = [];

        //loop through all the terms and create an associative array based on terms and results
        foreach ($terms as $term) {
            $result = Result::where('student_id', $student->id)
                ->where('academic_session_id', $academicSession->id)
                ->where('term_id', $term->id)->get();

            //Get each subject highest and lowest scores    
            foreach ($result as $item) {

                $scoresQuery = Result::where('academic_session_id', $academicSession->id)
                    ->where('term_id', $term->id)->where('subject_id', $item->subject->id);

                //highest scores
                $maxScore = $scoresQuery->max('total');

                $maxScore = [$item->subject->name . '-' . $term->name => $maxScore];
                $maxScores = array_merge($maxScores, $maxScore);

                //Lowest scores
                $minScore = $scoresQuery->min('total');

                $minScore = [$item->subject->name . '-' . $term->name => $minScore];
                $minScores = array_merge($minScores, $minScore);

                //Average Scores
                $averageScore = $scoresQuery->pluck('total');

                $averageScore = collect($averageScore)->avg();
                $averageScore = [$item->subject->name . '-' . $term->name => $averageScore];
                $averageScores = array_merge($averageScores, $averageScore);
            }

            $result = [$term->name => $result];
            $results = array_merge($results, $result);
        }

        return view('studentSessionalResults', compact('results', 'maxScores', 'minScores', 'averageScores'));
    }

    public function getTermResults(Request $request, $student)
    {

        $student = Student::findStudent($student);
        $this->validate($request, [
            'academicSession' => ['required', 'exists:academic_sessions,name'],
            'term' => ['required', 'exists:terms,name'],
        ]);

        $academicSession = AcademicSession::where('name', $request->academicSession)->first();
        $term = Term::where('name', $request->term)->first();
        $student = $student->first();

        $results = Result::where('student_id', $student->id)
            ->where('academic_session_id', $academicSession->id)
            ->where('term_id', $term->id)->get();

        $maxScores = [];
        $minScores = [];
        $averageScores = [];

        //Get each subject highest and lowest scores    
        foreach ($results as $result) {

            $scoresQuery = Result::where('academic_session_id', $academicSession->id)
                ->where('term_id', $term->id)->where('subject_id', $result->subject->id);

            //highest scores
            $maxScore = $scoresQuery->max('total');

            $maxScore = [$result->subject->name => $maxScore];
            $maxScores = array_merge($maxScores, $maxScore);

            //Lowest scores
            $minScore = $scoresQuery->min('total');

            $minScore = [$result->subject->name => $minScore];
            $minScores = array_merge($minScores, $minScore);

            //Average Scores
            $averageScore = $scoresQuery->pluck('total');
            $averageScore = collect($averageScore)->avg();
            $averageScore = [$result->subject->name => $averageScore];
            $averageScores = array_merge($averageScores, $averageScore);
        }
        return view('studentTermResults', compact('results', 'academicSession', 'term', 'maxScores', 'averageScores', 'minScores'));
    }

    public function getSubjects($student)
    {
        $student = Student::findStudent($student);
        $student = $student->first();
        $subjects = $student->classroom->subjects()->get();

        return response(200);
    }

    public function destroy($id, Student $student)
    {
        $this->authorize('delete', $student);

        $student = Student::findOrFail($id);

        $student->delete();

        return back()->with('success', 'Student Deleted');
    }

    public function forceDelete($id, Student $student)
    {
        $this->authorize('forceDelete', $student);
        $student = Student::findOrFail($id);
        $student->forceDelete();
        return response(200);

        /**
         * TODO 
         * check if student has a guardian before deleting 
         * then delete the guardian if it has only one child
         */
    }
}
