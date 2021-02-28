<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\PD;
use App\Models\PDType;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;

class PDController extends Controller
{
    public function create($id, $termId)
    {
        $student = Student::findOrFail($id);
        $term = Term::findOrFail($termId);
        $pdTypes = PDType::all();
        $currentAcademicSession = AcademicSession::currentAcademicSession();
        $studentPDs = $student->pds()->where('academic_session_id', $currentAcademicSession->id)->where('term_id', $termId)->get();
        $pdTypesValues = [];

        foreach ($studentPDs as $studentPD) {
            $pdTypeValue = [$studentPD->p_d_type_id => $studentPD->value];
            $pdTypesValues += $pdTypeValue;
        }
        return view('createPD', compact('pdTypes', 'student', 'pdTypesValues', 'term'));
    }

    //remove term here cos it's redundant
    public function store($id, $termId, Request $request)
    {
        $student = Student::findOrFail($id);
        $term = Term::findOrFail($termId);

        $validatedData = $request->validate([
            'pdTypes.*' => ['required', 'numeric', 'min:1', 'max:5'],
        ]);

        $currentAcademicSession = AcademicSession::currentAcademicSession();

        foreach ($validatedData['pdTypes'] as $pdType => $value) {
            $pdType = PDType::where('slug', $pdType)->first();
            PD::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_session_id' => $currentAcademicSession->id,
                    'term_id' => $term->id,
                    'p_d_type_id' => $pdType->id,
                ],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Record Added for current session');
    }
}
