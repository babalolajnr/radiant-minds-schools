<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PeriodController extends Controller
{
    use ValidationTrait;

    /**
     * get periods page
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $periods = Period::all();
        $academicSessions = AcademicSession::all();
        $terms = Term::all();
        return view('periods', compact('periods', 'academicSessions', 'terms'));
    }

    /**
     * store period.
     *
     * @param  mixed $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $academicSession = AcademicSession::where('name', $request->academic_session)->first();

        if ($academicSession == null) {
            return back()->with('error', 'Academic Session not found');
        }

        $messages = [
            'start_date.after_or_equal' => 'Start date must be after or equal to the start date of the selected academic session',
            'end_date.before_or_equal' => 'End date must be before or equal to the end date of the selected academic session'
        ];

        $data = $request->validate([
            'academic_session' => ['required', 'exists:academic_sessions,name', 'string'],
            'term' => ['required', 'string', 'exists:terms,name'],
            'start_date' => ['required', 'date', 'unique:periods,start_date', "after_or_equal:{$academicSession->start_date}"],
            'end_date' => ['required', 'date', 'after:start_date', 'unique:periods,end_date', "before_or_equal:{$academicSession->end_date}"],
        ], $messages);

        //check if academic session and term exist on the same row
        $row = Period::where('academic_session_id', $data['academic_session'])->where('term_id', $data['term']);

        if ($row->exists()) {
            return back()->with('error', 'Record Exists');
        }

        //check if date range is unique
        $validateDateRange = $this->validateDateRange($data['start_date'], $data['end_date'], Period::class);

        if ($validateDateRange !== true) {
            throw ValidationException::withMessages([
                'start_date' => ['Date range overlaps with another period'],
                'end_date' => ['Date range overlaps with another period']
            ]);
        }

        $term = Term::where('name', $data['term'])->first();

        //create slug
        $slug = Str::of("{$academicSession->name} {$term->slug}")->slug('-');

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
     * edit period
     *
     * @param  Period $period
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Period $period)
    {
        return view('editPeriod', compact('period'));
    }

    /**
     * update period
     *
     * @param  Request $request
     * @param  Period $period
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Period $period)
    {
        $data = $request->validate([
            'start_date' => ['required', 'date', Rule::unique('periods')->ignore($period), "after_or_equal:{$period->academicSession->start_date}"],
            'end_date' => ['required', 'date', 'after:start_date', Rule::unique('periods')->ignore($period), "before_or_equal:{$period->academicSession->end_date}"],
        ]);

        //check if date range is unique and does not overlap another date range
        $validateDateRange = $this->validateDateRange($data['start_date'], $data['end_date'], Period::class, $period);

        if ($validateDateRange !== true) {
            return back()->with('error', 'Date range overlaps with another period');
        }

        $period->update($data);

        return back()->with('success', 'Period updated successfully');
    }

    /**
     * Set active period
     *
     * @param  mixed $period
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setActivePeriod(Period $period)
    {
        $activePeriod = Period::where('active', true)->first();

        if ($activePeriod != null) {
            $activePeriod->update(['active' => null]);
        }

        $period->update(['active' => true]);

        return back()->with('success', "{$period->academicSession->name} {$period->term->name} is now active");
    }

    /**
     * destroy period
     *
     * @param  mixed $period
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Period $period)
    {

        try {
            $period->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                //SQLSTATE[23000]: Integrity constraint violation
                return back()->with('error', 'Period cannot be deleted because some resources are dependent on it!');
            }
        }
        return back()->with('success', 'Deleted!');
    }
}
