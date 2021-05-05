<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $validateDateRange = $this->validateDateRange($data['start_date'], $data['end_date']);

        //check if date range is unique
        if ($validateDateRange !== true) {
            return back()->with('error', 'Date range overlaps with another period');
        }

        $academicSession = AcademicSession::where('name', $data['academic_session'])->first();
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

    /**
     * Validate date range is unique
     * 
     * For Two Date ranges A and B
     * Formula : StartA <= EndB && EndA >= StartB
     * 
     * If the function returns true, date range is unique
     * else it is not
     *
     * @param  string $startDate
     * @param  string $endDate
     * @return bool
     */
    private function validateDateRange($startDate, $endDate): bool
    {
        $periods = Period::all();

        if (count($periods) < 1) {
            return true;
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);

        foreach ($periods as $period) {

            $periodStartDate =  Carbon::createFromFormat('Y-m-d', $period->start_date);
            $periodEndDate =  Carbon::createFromFormat('Y-m-d', $period->end_date);

            if ($periodStartDate->lessThanOrEqualTo($endDate) && $periodEndDate->greaterThanOrEqualTo($startDate)) {
                return false;
            }
        }

        return true;
    }
}
