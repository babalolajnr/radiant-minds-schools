<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\AD;
use App\Models\ADType;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;

class ADController extends Controller
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
        $adTypes = ADType::all();

        $academicSession = is_null($academicSessionName) ? AcademicSession::currentAcademicSession() : AcademicSession::where('name', $academicSessionName)->firstOrFail();

        //get student ads for academic session and term passed into the controller
        $studentADs = $student->ads()->where('academic_session_id', $academicSession->id)->where('term_id', $term->id);
        if ($studentADs->exists()) {
            $adTypesValues = [];

            $studentADs = $studentADs->get();

            //create an associative array of pdtypeid and value from the pd model
            foreach ($studentADs as $studentAD) {
                $adTypeValue = [$studentAD->a_d_type_id => $studentAD->value];
                $adTypesValues += $adTypeValue;
            }
        } else {
            $adTypesValues = null;
        }

        return view('createAD', compact('adTypes', 'student', 'adTypesValues', 'term', 'academicSession'));
    }

    /**
     * this method stores ads id they don't exist
     * and updates them if they do. It should probably be called
     * storeOrUpdate but I would probably change it later. It also
     * recieves an optional academic session id parameter.
     * 
     * If the optional academic session id parameter is null, it
     * uses the current academic session to store the adType else it
     * uses the academic session from the url
     */
    public function store(Student $student, $termId, Request $request, $academicSessionId = null)
    {
        $term = Term::findOrFail($termId);

        $validatedData = $request->validate([
            'adTypes.*' => ['required', 'numeric', 'min:1', 'max:5'],
        ]);

        $academicSession = is_null($academicSessionId) ?
            AcademicSession::currentAcademicSession() : AcademicSession::findOrFail($academicSessionId);

        foreach ($validatedData['adTypes'] as $adType => $value) {
            $adType = ADType::where('slug', $adType)->first();
            AD::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_session_id' => $academicSession->id,
                    'term_id' => $term->id,
                    'a_d_type_id' => $adType->id,
                ],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Record Added');
    }
}
