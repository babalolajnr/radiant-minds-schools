<?php

namespace App\Http\Controllers;

use App\Models\AD;
use App\Models\ADType;
use App\Models\Period;
use App\Models\Student;
use Illuminate\Http\Request;

class ADController extends Controller
{
    /**
     * Get affective domain creation form
     * 
     * This method accepts an optional periodSlug parameter
     * if the request does not have periodSlug it defaults to the
     * active period
     * 
     * @param Student $student
     * @param string $periodSlug
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     * 
     */
    public function create(Student $student, $periodSlug = null)
    {
        $adTypes = ADType::all();

        $period = is_null($periodSlug)
            ? Period::activePeriod()
            : Period::where('slug', $periodSlug)->firstOrFail();

        //get student ads for period and term passed into the controller
        $studentADs = $student->ads()->where('period_id', $period->id);
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

        return view('createAD', compact('adTypes', 'student', 'adTypesValues', 'period'));
    }

    /**
     * Store or update AD record
     * 
     * If the optional periodSlug parameter is null, it
     * uses the active period to store the adType else it
     * uses the period from the url
     * 
     * @param Student $student
     * @param Request $request
     * @param string $periodSlug
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeOrUpdate(Student $student, Request $request, $periodSlug = null)
    {

        $validatedData = $request->validate([
            'adTypes.*' => ['required', 'numeric', 'min:1', 'max:5'],
        ]);

        $period = is_null($periodSlug) ?
            period::activePeriod() : Period::where('slug', $periodSlug)->firstOrFail();

        foreach ($validatedData['adTypes'] as $adType => $value) {
            $adType = ADType::where('slug', $adType)->first();
            AD::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'period_id' => $period->id,
                    'a_d_type_id' => $adType->id,
                ],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Record Added');
    }
}
