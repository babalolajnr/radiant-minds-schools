<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodRequest;
use App\Http\Requests\UpdatePeriodRequest;
use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use App\Services\PeriodService;

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
     * @param  StorePeriodRequest $request
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
     * @param  UpdatePeriodRequest $request
     * @param  Period $period
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePeriodRequest $request, Period $period)
    {
        $period->update($request->validated());

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
