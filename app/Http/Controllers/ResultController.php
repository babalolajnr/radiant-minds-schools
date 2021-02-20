<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
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
        $subjects = $student->classroom->subjects()->get();

        return view('createResults', compact('terms', 'subjects', 'student'));
    }

    public function store(Request $request, $studentID)
    {
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
            return back()->with('error', 'Current academic session not selected 😢');
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

        $results = Result::where('student_id', $student->id)
            ->where('academic_session_id', $academicSession->id)
            ->where('term_id', $term->id)->get();

        $maxScores = [];
        $minScores = [];
        $averageScores = [];
        $totalObtained = 0;
        $totalObtainable = count($results) * 100;

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

            //total obtained score
            $totalObtained += $result->total;
        }

        $percentage = $totalObtained/$totalObtainable * 100;
        
        return view('performanceReport', compact('student', 'totalObtained', 'totalObtainable', 'percentage', 'results', 'academicSession', 'term', 'maxScores', 'averageScores', 'minScores'));
    }
}
