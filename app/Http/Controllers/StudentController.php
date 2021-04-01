<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\PDType;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use  Intervention\Image\Facades\Image;

class StudentController extends Controller
{

     /**
     * @return array
     * @param mixed $validatedData
     * 
     * returns student info after it been extracted from
     * the validated data
     */
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
        ];
    }

    /**
     * @return array
     * @param Student $student
     * 
     * return student validation rules
     */
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
            'classroom' => ['required', 'string']
        ];
    }

    public function index()
    {
        $students = Student::whereNull('graduated_at')->get()->sortByDesc('created_at');
        $academicSessions = AcademicSession::all()->sortByDesc('created_at');
        $terms = Term::all()->sortByDesc('created_at');
        return view('students', compact('students', 'academicSessions', 'terms'));
    }

    public function getAlumni()
    {
        $students = Student::whereNotNull('graduated_at')->get();
        return view('alumni', compact('students'));
    }

    public function create()
    {
        $classrooms = Classroom::pluck('name')->all();
        return view('createStudent', compact('classrooms'));
    }

    /**
     * This method works by collecting all the guardian and student info from the user and
     * making sure it's all filled out. Then it checks if the guardian's phone number is present
     * in the database. If it is then it gets the guardian's id and inserts it into the student's table
     */
    public function store(Request $request)
    {
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

        return redirect()->route('student.index')->with('success', 'Student Added!');
    }

    public function show(Student $student)
    {
        //get unique results that has unique academic sessions
        $results = Result::where('student_id', $student->id)->get()->unique('academic_session_id');

        //reset the keys to consecutively numbered indexes
        $results = $results->values()->all();

        $academicSessions = [];

        foreach ($results as $result) {
            $academicSession = $result->academicSession;
            array_push($academicSessions, $academicSession);
        }

        $academicSessions = collect($academicSessions);

        $terms = Term::all();
        return  view('showStudent', compact('student', 'academicSessions', 'terms'));
    }


    public function activate(Student $student)
    {

        $student->is_active = true;
        $student->save();

        return back()->with('success', 'Student Activated!');
    }

    public function deactivate(Student $student)
    {

        $student->is_active = false;
        $student->save();

        return back()->with('success', 'Student Deactivated!');
    }

    public function edit(Student $student)
    {
        $classrooms = Classroom::pluck('name')->all();
        return view('editStudent', compact(['student', 'classrooms']));
    }

    public function update(Student $student, Request $request)
    {

        $validatedData = $request->validate($this->studentValidationRules($student));
        $student->update($this->studentInfo($validatedData));

        return redirect('/edit/student/' . $student->admission_no)->with('success', 'Student Updated!');
    }

    public function getSessionalResults(Student $student, $academicSessionName)
    {
        $academicSession = AcademicSession::where('name', $academicSessionName)->firstOrFail();

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

        return view('studentSessionalResults', compact('results', 'maxScores', 'minScores', 'averageScores', 'academicSession'));
    }

    public function getTermResults(Student $student, $termSlug, $academicSessionName)
    {

        $academicSession = AcademicSession::where('name', $academicSessionName)->firstOrFail();
        $term = Term::where('slug', $termSlug)->firstOrFail();

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
        return view('studentTermResults', compact('student', 'results', 'academicSession', 'term', 'maxScores', 'averageScores', 'minScores'));
    }

    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);

        $student->delete();

        return back()->with('success', 'Student deleted');
    }

    public function forceDelete($id, Student $student)
    {
        $this->authorize('delete', $student);

        $student = Student::withTrashed()->findOrFail($id);
        $guardian = $student->guardian;
        $guardianChildren = $guardian->children()->withTrashed()->get();

        //delete student image if it exists
        if (!is_null($student->image)) {
            $deletePath = $student->image;
            $deletePath = str_replace('storage/', '', $deletePath);
            $deletePath = 'public/' . $deletePath;

            Storage::delete($deletePath);
        }

        /**if guardian has more than one child delete only the student's 
         * data else delete the student and the guargian's data
         */
        if (count($guardianChildren) > 1) {
            $student->forceDelete();
        } else {
            $student->forceDelete();
            $guardian->delete();
        }

        return back()->with('success', 'Student deleted permanently');
    }

    public function uploadImage(Student $student, Request $request)
    {

        $validatedData = $request->validate([
            'image' => ['required', 'image', 'unique:students,image,except,id']
        ]);

        //create name from first and last name
        $imageName = $student->first_name . $student->last_name . '.' . $request->image->extension();
        $path = $request->file('image')->storeAs('public/students', $imageName);
        Image::make($request->image->getRealPath())->fit(400, 400)->save(storage_path('app/' . $path));

        //update image in the database
        $filePath = 'storage/students/' . $imageName;
        $student->image = $filePath;
        $student->save();

        return back()->with('success', 'Image uploaded successfully');
    }

    public function showStudentSettingsView(Student $student)
    {
        $currentAcademicSession = AcademicSession::currentAcademicSession();

        if (is_null($currentAcademicSession)) {
            return back()->with('error', 'Current Academic Session is not set');
        }

        $pdTypes = PDType::all();
        $terms = Term::all();

        return view('studentSettings', compact('student', 'pdTypes', 'currentAcademicSession', 'terms'));
    }

    public function promote(Student $student)
    {

        $classRank = $student->classroom->rank;
        $highestClassRank = Classroom::max('rank');

        if ($classRank !== $highestClassRank) {
            $newClassRank = $classRank + 1;
            $newClassId = Classroom::where('rank', $newClassRank)->first()->id;
            $student->classroom_id = $newClassId;
            $student->save();

            return back()->with('success', 'Student Promoted!');
        }

        return back()->with('error', 'Student is in the Maximum class possible');
    }

    public function demote(Student $student)
    {

        $classRank = $student->classroom->rank;
        $lowestClassRank = Classroom::min('rank');

        //if the student is not in the lowest class then demote the student
        if ($classRank !== $lowestClassRank) {
            $newClassRank = $classRank - 1;
            $newClassId = Classroom::where('rank', $newClassRank)->first()->id;
            $student->classroom_id = $newClassId;

            // if student has graduated, 'ungraduate' the student
            if (!is_null($student->graduated_at)) {
                $student->graduated_at = null;
            }

            $student->save();

            return back()->with('success', 'Student Demoted!');
        }

        return back()->with('error', 'Student is in the Lowest class possible');
    }

    public function showTrashed()
    {
        $students = Student::onlyTrashed()->get();

        return view('studentTrash', compact('students'));
    }

    public function restore($id)
    {
        $student = Student::withTrashed()->findOrFail($id);
        $student->restore();

        return back()->with('success', 'Student restored!');
    }

    public function graduate(Student $student)
    {
        $student->graduated_at = now();
        $student->is_active = false;
        $student->save();

        return back()->with('success', 'Student Graduated!');
    }
}
