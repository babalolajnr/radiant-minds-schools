<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\Classroom;
use App\Models\PDType;
use App\Models\Period;
use App\Models\Student;
use App\Models\Term;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use  Intervention\Image\Facades\Image;

class StudentController extends Controller
{

   
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
     * store student
     *
     * @param  StoreStudentRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreStudentRequest $request)
    {
        $studentService = new StudentService();
        $studentService->store($request);

        return redirect()->route('student.index')->with('success', 'Student Added!');
    }

    public function show(Student $student)
    {
        //get unique results that has unique academic sessions
        $results = Result::where('student_id', $student->id)->get()->unique('period_id');

        //reset the keys to consecutively numbered indexes
        $results = $results->values()->all();
        $academicSessions = [];

        foreach ($results as $result) {
            $academicSession = $result->period->academicSession;
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

    public function update(Student $student, UpdateStudentRequest $request)
    {
        $student->update($request->validated());
        return redirect(route('student.edit', ['student' => $student]))->with('success', 'Student Updated!');
    }

    public function getSessionalResults(Student $student, $academicSessionName)
    {
        $academicSession = AcademicSession::where('name', $academicSessionName)->firstOrFail();
        $periods = Period::where('academic_session_id', $academicSession->id)->get();

        $results = [];
        $maxScores = [];
        $minScores = [];
        $averageScores = [];

        //loop through all the terms and create an associative array based on terms and results
        foreach ($periods as $period) {
            $resultItem = Result::where('student_id', $student->id)
                ->where('period_id', $period->id)->get();

            //Get each subject highest and lowest scores    
            foreach ($resultItem as $item) {

                $scoresQuery = Result::where('period_id', $period->id)->where('subject_id', $item->subject->id);

                //highest scores
                $maxScore = $scoresQuery->max('total');

                $maxScore = [$item->subject->name . '-' . $period->term->name => $maxScore];
                $maxScores = array_merge($maxScores, $maxScore);

                //Lowest scores
                $minScore = $scoresQuery->min('total');

                $minScore = [$item->subject->name . '-' . $period->term->name => $minScore];
                $minScores = array_merge($minScores, $minScore);

                //Average Scores
                $averageScore = $scoresQuery->pluck('total');

                $averageScore = collect($averageScore)->avg();
                $averageScore = [$item->subject->name . '-' . $period->term->name => $averageScore];
                $averageScores = array_merge($averageScores, $averageScore);
            }

            $resultItem = [$period->term->name => $resultItem];
            $results = array_merge($results, $resultItem);
        }

        return view('studentSessionalResults', compact('results', 'maxScores', 'minScores', 'averageScores', 'academicSession'));
    }

    public function getTermResults(Student $student, $termSlug, $academicSessionName)
    {

        $academicSession = AcademicSession::where('name', $academicSessionName)->firstOrFail();
        $term = Term::where('slug', $termSlug)->firstOrFail();
        $period = Period::where('academic_session_id', $academicSession->id)->where('term_id', $term->id)->first();


        $results = Result::where('student_id', $student->id)->where('period_id', $period->id)->get();

        $maxScores = [];
        $minScores = [];
        $averageScores = [];

        //Get each subject highest and lowest scores    
        foreach ($results as $result) {

            $scoresQuery = Result::where('period_id', $period->id)
                ->where('subject_id', $result->subject->id);

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
        return view('studentTermResults', compact('student', 'results', 'academicSession', 'term', 'maxScores', 'averageScores', 'minScores', 'period'));
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
        $currentAcademicSession = Period::activePeriod()->academicSession;

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
