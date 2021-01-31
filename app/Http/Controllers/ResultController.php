<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ResultController extends Controller
{
    public function store(Request $request, $studentID, $subjectID)
    {
        $student = Student::findOrFail($studentID);
        $subject = Subject::findOrFail($subjectID);
        
        if (!$request->assessment) {
            throw ValidationException::withMessages(['assessment' => 'Assessment is required']);
        }

        $assessment = Assessment::where('name', $request->assessment)->first();
        $assessmentMaxScore = $assessment->assessmentType->max_score;

        $messages = [
            'between' => 'The score must be between 0 and ' . $assessmentMaxScore
        ];

        $this->validate($request, [
            'mark' => ['required', 'numeric', 'between:0,' . $assessmentMaxScore],
            'assessment' => ['string']
        ], $messages);

        Result::create([
            'assessment_id' => $assessment->id,
            'subject_id' => $subject->id,
            'student_id' => $student->id,
            'mark' => $request->mark
        ]);

    }
}
