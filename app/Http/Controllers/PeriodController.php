<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_session' => ['required', 'exists:academic_sessions,name', 'string'],
            'term' => ['required', 'string', 'exists:terms,name'],
            'start_date' => ['required', 'date', 'unique:periods'],
            'end_date' => ['required', 'date', 'after:start_date', 'unique:periods'],
        ]);

        //check if academic session and term exist on the same row
        $row = Period::where('academic_session_id', $data['academic_session'])->where('term_id', $data['term']);

        if ($row->exists()) {
            return back()->with('error', 'Record Exists');
        }

        $academicSessionID = AcademicSession::where('name', $data['academic_session'])->first()->id;
        $termID = Term::where('name', $data['term'])->first()->id;

        Period::create([
            'academic_session_id' => $academicSessionID,
            'term_id' => $termID,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date']
        ]);

        return back()->with('success', 'Record Created!');
    }
}
