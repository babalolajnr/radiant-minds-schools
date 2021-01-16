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

        $term = Term::where('name', $request->term)->first();
        $termID = $term->id;
        $assessmentType = AssessmentType::where('name', $request->assessment_type)->first();
        $academicSession = AcademicSession::where('name', $request->academic_session)->first();
        $assessmentTypeID = $assessmentType->id;
        $academicSessionID = $academicSession->id;

        $checkUniqueness = Assessment::where('term_id', $termID)->where('assessment_type_id', $assessmentTypeID)->where('academic_session_id', $academicSessionID)->first();

        if(!empty($checkUniqueness)){
            throw ValidationException::withMessages(['term' => 'Record exist']);
        }

        $name = $assessmentType->name . ' ' . $term->name . ' ' . $academicSession->name;
        Assessment::create([
            'term_id' => $termID,
            'assessment_type_id' => $assessmentTypeID,
            'academic_session_id' => $academicSessionID,
            'name' =>  $name
        ]);

        return response(200);
    }
}
