<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PeriodController extends Controller
{
    use ValidationTrait;
        
    /**
     * store period.
     *
     * @param  mixed $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $academicSession = AcademicSession::where('name', $request->academic_session)->first();

        if($academicSession == null){
            return back()->with('error', 'Academic Session not found');
        }

        $data = $request->validate([
            'academic_session' => ['required', 'exists:academic_sessions,name', 'string'],
            'term' => ['required', 'string', 'exists:terms,name'],
            'start_date' => ['required', 'date', 'unique:periods', "after_or_equal:{$academicSession->start_date}"],
            'end_date' => ['required', 'date', 'after:start_date', 'unique:periods', "before_or_equal:{$academicSession->end_date}"],
        ]);

        //check if academic session and term exist on the same row
        $row = Period::where('academic_session_id', $data['academic_session'])->where('term_id', $data['term']);

        if ($row->exists()) {
            return back()->with('error', 'Record Exists');
        }

        //check if date range is unique
        $validateDateRange = $this->validateDateRange($data['start_date'], $data['end_date'], Period::class);

        if ($validateDateRange !== true) {
            return back()->with('error', 'Date range overlaps with another period');
        }

        $term = Term::where('name', $data['term'])->first();

        $slug = Str::of("{$academicSession->slug} {$term->slug}")->slug('-');

        $periodCount = Period::count();

        //check if table has records
        if ($periodCount < 1) {
            $highestRank = 1;
        } else {
            $highestRank = Period::max('rank');
            $highestRank++;
        }

        Period::create([
            'academic_session_id' => $academicSession->id,
            'term_id' => $term->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'slug' => $slug,
            'rank' => $highestRank
        ]);

        return back()->with('success', 'Record Created!');
    }

}
