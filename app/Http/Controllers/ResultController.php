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
    public function store(Request $request, $studentID, $subjectID)
    {
        $student = Student::findOrFail($studentID);
        $subject = Subject::findOrFail($subjectID);

        $messages = [
            'between' => 'The score must be between 0 and 40',
        ];

        $validatedData = $request->validate([
            'ca' => ['numeric', 'between:0,40'],
            'exam' => ['numeric', 'between:0,60'],
            'academicSession' => ['string', 'required', 'exists:academic_sessions,name'],
            'term' => ['string', 'required', 'exists:terms,name'],
        ], $messages);

        $term = Term::where('name', $validatedData['term'])->first();
        $academicSession = AcademicSession::where('name', $validatedData['academicSession'])->first();

        $record = Result::where('subject_id', $subjectID)
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

        return response(200);
    }
}
