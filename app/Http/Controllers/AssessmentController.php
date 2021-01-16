<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AssessmentController extends Controller
{
    public function index()
    {
        $assessments = Assessment::all();
        return response(200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'term' => ['required', 'string'],
            'assessment_type' => ['required', 'string'],
            'academic_session' => ['required', 'string'],
        ]);

        $termID = Term::where('name', $request->term)->first()->id;
        $assessmentTypeID = AssessmentType::where('name', $request->assessment_type)->first()->id;
        $academicSessionID = AcademicSession::where('name', $request->academic_session)->first()->id;

        $checkUniqueness = Assessment::where('term_id', $termID)->where('assessment_type_id', $assessmentTypeID)->where('academic_session_id', $academicSessionID)->first();

        if(!empty($checkUniqueness)){
            throw ValidationException::withMessages(['term' => 'Record exist']);
        }

        Assessment::create([
            'term_id' => $termID,
            'assessment_type_id' => $assessmentTypeID,
            'academic_session_id' => $academicSessionID,
        ]);

        return response(200);
    }
}
