<?php

namespace App\Http\Controllers;

use App\Models\AssessmentType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssessmentTypeController extends Controller
{
    public function index()
    {
        $assessmentTypes = AssessmentType::all();
        return response(200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'unique:assessment_types'],
            'max_score' => ['required', 'numeric'],
        ]);

        AssessmentType::create($request->all());
        return response(200);
    }

    public function edit($id)
    {

        $assessmentType = AssessmentType::findOrFail($id);
        return response(200);
    }

    public function update($id, Request $request)
    {
        $assessmentType = AssessmentType::findOrFail($id);

        $this->validate($request, [
            'name' => ['required', 'string', Rule::unique('assessment_types')->ignore($assessmentType)],
            'max_score' => ['required', 'numeric'],
        ]);
        $assessmentType->update($request->all());
        return response(200);
    }

    public function destroy($id, AssessmentType $assessmentType)
    {
        $this->authorize('delete', $assessmentType);
        $assessmentType = AssessmentType::findOrFail($id);
        $assessmentType->delete();
        return response(200);
    }
}
