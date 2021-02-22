<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\PD;
use App\Models\PDType;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PDController extends Controller
{
    public function create($id)
    {
        $student = Student::findOrFail($id);
        $pdTypes = PDType::all();
        $terms = Term::all();
        return view('createPD', compact('pdTypes', 'student', 'terms'));
    }

    public function store($id, Request $request)
    {
        $student = Student::findOrFail($id);
        $validatedData = $request->validate([
            'pdTypes.*' => ['required', 'numeric', 'min:1', 'max:5'],
            'term' => ['required', 'string', 'exists:terms,name']
        ]);

        $term = Term::where('name', $validatedData['term'])->first();
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
