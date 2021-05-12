<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodRequest;
use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use App\Services\PeriodService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PeriodController extends Controller
{

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
    public function store(StorePeriodRequest $request)
    {
        $periodService = new PeriodService();

        $periodService->store($request);

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
            throw ValidationException::withMessages([
                'start_date' => ['Date range overlaps with another period'],
                'end_date' => ['Date range overlaps with another period']
            ]);
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
