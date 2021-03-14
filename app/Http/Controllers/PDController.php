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
    /**
     * This method accepts an optional academic session id parameter
     * if the request does not have academic session id it defaults to the
     * current academic session
     * 
     * 
     */
    public function create(Student $student, $termSlug, $academicSessionName = null)
    {
        $term = Term::where('slug', $termSlug)->firstOrFail();
        $pdTypes = PDType::all();

        $academicSession = is_null($academicSessionName) ? AcademicSession::currentAcademicSession() : AcademicSession::where('name', $academicSessionName)->firstOrFail();

        $studentPDs = $student->pds()->where('academic_session_id', $academicSession->id)->where('term_id', $term->id);
        if ($studentPDs->exists()) {
            $pdTypesValues = [];

            $studentPDs = $studentPDs->get();

            //create an associative array of pdtypeid and value from the pd model
            foreach ($studentPDs as $studentPD) {
                $pdTypeValue = [$studentPD->p_d_type_id => $studentPD->value];
                $pdTypesValues += $pdTypeValue;
            }
        } else {
            $pdTypesValues = null;
        }

        // $currentAcademicSession = AcademicSession::currentAcademicSession();

        return view('createPD', compact('pdTypes', 'student', 'pdTypesValues', 'term', 'academicSession'));
    }

    /**
     * this method stores pds id they don't exist
     * and updates them if they do. It should probably be called
     * storeOrUpdate but I would probably change it later. It also
     * recieves an optional academic session id parameter.
     * 
     * If the optional academic session id parameter is null, it
     * uses the current academic session to store the pdType else it
     * uses the academic session from the url
     */
    public function store(Student $student, $termId, Request $request, $academicSessionId = null)
    {
        $term = Term::findOrFail($termId);

        $validatedData = $request->validate([
            'pdTypes.*' => ['required', 'numeric', 'min:1', 'max:5'],
        ]);

        $academicSession = is_null($academicSessionId) ?
            AcademicSession::currentAcademicSession() : AcademicSession::findOrFail($academicSessionId);

        foreach ($validatedData['pdTypes'] as $pdType => $value) {
            $pdType = PDType::where('slug', $pdType)->first();
            PD::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_session_id' => $academicSession->id,
                    'term_id' => $term->id,
                    'p_d_type_id' => $pdType->id,
                ],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Record Added');
    }
}
