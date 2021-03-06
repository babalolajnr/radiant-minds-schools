<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\PD;
use App\Models\PDType;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * there should ba a page that has all the courses the student's
     * class has and a button to fill in the subject, term, session
     * ca and exam
     *
     */
    public function create($student)
    {
        $student = Student::findStudent($student);
        $student = $student->first();
        $terms = Term::all();
        $currentAcademicSession = AcademicSession::currentAcademicSession();
        $subjects = $student->classroom->subjects()->where('academic_session_id',  $currentAcademicSession->id)->get();

        return view('createResults', compact('terms', 'subjects', 'student', 'currentAcademicSession'));
    }

    public function store(Request $request, $studentID)
    {
        /**
         * NOTE: Result can only be stored for the current academic session
         */
        $student = Student::findOrFail($studentID);

        $messages = [
            'between' => 'The score must be between 0 and 40',
        ];

        $validatedData = $request->validate([
            'ca' => ['required', 'numeric', 'between:0,40'],
            'exam' => ['nullable', 'numeric', 'between:0,60'],
            'term' => ['string', 'required', 'exists:terms,name'],
            'subject' => ['string', 'required', 'exists:subjects,name']
        ], $messages);

        $term = Term::where('name', $validatedData['term'])->first();
        $subject = Subject::where('name', $validatedData['subject'])->first();
        $academicSession = AcademicSession::currentAcademicSession();

        if (is_null($academicSession)) {
            return back()->with('error', 'Current academic session has not been set 😢');
        }

        $record = Result::where('subject_id', $subject->id)
            ->where('student_id', $studentID)
            ->where('term_id', $term->id)
            ->where('academic_session_id', $academicSession->id);

        if ($record->exists()) {
            return back()->with('error', 'Record Exists');
        }

        $exam = $validatedData['exam'] ?? 0;
        $ca = $validatedData['ca'] ?? 0;

        Result::create([
            'ca' => $ca,
            'exam' => $exam,
            'term_id' => $term->id,
            'academic_session_id' => $academicSession->id,
            'subject_id' => $subject->id,
            'student_id' => $student->id,
            'total' => $exam + $ca
        ]);

        return back()->with('success', 'Record created! 👍');
    }

    public function showPerformanceReport($student, $academicSessionId, $termId)
    {
        $student = Student::findStudent($student);
        $academicSession = AcademicSession::findOrFail($academicSessionId);
        $term = Term::findOrFail($termId);

        $student = $student->first();

        $pdTypes = PDType::all();

        // get pds for the academic session and term
        $pds = $student->pds()->where('academic_session_id', $academicSession->id)->where('term_id', $term->id)->get();
        
        $pdTypeIds = [];
        $values = [];

        //for each of the pds push the pdTypeId and pd value into two separate arrays
        foreach ($pds as $pd) {
            $pdTypeId = $pd->p_d_type_id;
            $value = $pd->value;
            array_push($pdTypeIds, $pdTypeId);
            array_push($values, $value);
        }

        //for each pdTypeId get the name and push it into an array
        $pdTypeNames = [];
        foreach ($pdTypeIds as $pdTypeId) {
            $pdTypeName = PDType::find($pdTypeId)->name;
            array_push($pdTypeNames, $pdTypeName);
        }

        //comnine the values array and the names array to form a new associative pds array
        $pds = array_combine($pdTypeNames, $values);

        //Get the subjects for the student's class in the selected academic session
        $subjects = $student->classroom->subjects()->where('academic_session_id',  $academicSession->id)->get();

        //Check if the class has subjects
        if (count($subjects) < 1) {
            return redirect('/view/classroom/' . $student->classroom->id)->with('error', 'The student\'s class does not have subjects set for the selected academic session');
        }
        
        $results = [];

        //create a results array from all subjects from the student's class
        foreach ($subjects as $subject) {
            $result = Result::where('student_id', $student->id)
                ->where('academic_session_id', $academicSession->id)
                ->where('term_id', $term->id)->where('subject_id', $subject->id)->first();

            $result = [$subject->name => $result];
            $results = array_merge($results, $result);
        }

        $maxScores = [];
        $minScores = [];
        $averageScores = [];
        $totalObtained = 0;
        $totalObtainable = count($subjects) * 100;
        $currentDate = now()->year;
        $yearOfBirth = Carbon::createFromFormat('Y-m-d', $student->date_of_birth)->format('Y');
        $age = $currentDate - $yearOfBirth;

        //Get class score statistics
        foreach ($results as $key => $result) {

            if ($result == null) {
                $maxScore = [$key => null];
                $maxScores = array_merge($maxScores, $maxScore);

                $minScore = [$key => null];
                $minScores = array_merge($minScores, $minScore);

                $averageScore = [$key => null];
                $averageScores = array_merge($averageScores, $averageScore);
            } else {
                $scoresQuery = Result::where('academic_session_id', $academicSession->id)
                    ->where('term_id', $term->id)->where('subject_id', $result->subject->id);

                //highest scores
                $maxScore = $scoresQuery->max('total');

                $maxScore = [$key => $maxScore];
                $maxScores = array_merge($maxScores, $maxScore);

                //Lowest scores
                $minScore = $scoresQuery->min('total');

                $minScore = [$key => $minScore];
                $minScores = array_merge($minScores, $minScore);

                //Average Scores
                $averageScore = $scoresQuery->pluck('total');
                $averageScore = collect($averageScore)->avg();
                $averageScore = [$key => $averageScore];
                $averageScores = array_merge($averageScores, $averageScore);

                //total obtained score
                $totalObtained += $result->total;
            }
        }

        $percentage = $totalObtained / $totalObtainable * 100;

        return view('performanceReport', compact(
            'student',
            'totalObtained',
            'totalObtainable',
            'percentage',
            'results',
            'academicSession',
            'term',
            'maxScores',
            'averageScores',
            'minScores',
            'age',
            'pds',
            'pdTypes'
        ));
    }

    public function edit($id)
    {
        $result = Result::findOrFail($id);

        //store previous url in session to be used for redirect after update
        session(['resultsPage' => url()->previous()]);
        return view('editResult', compact('result'));
    }

    public function update($id, Request $request)
    {
        $result = Result::findOrFail($id);
        $validatedData = $request->validate([
            'ca' => ['required', 'numeric', 'between:0,40'],
            'exam' => ['nullable', 'numeric', 'between:0,60'],
        ]);
        $exam = $validatedData['exam'] ?? 0;
        $ca = $validatedData['ca'] ?? 0;
        $total = $exam + $ca;
        $total = ['total' => $total];
        $result->update($validatedData + $total);

        //return to previously viewed route b4 edit page
        return redirect($request->session()->get('resultsPage'))->with('success', 'Result Updated!');
    }

    public function destroy($id)
    {
        $result = Result::findOrFail($id);
        $result->delete();

        return back()->with('success', 'Result Deleted');
    }
}
