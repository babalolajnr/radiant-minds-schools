<?php

namespace App\Http\Controllers;

use App\Models\PD;
use App\Models\PDType;
use App\Models\Period;
use App\Models\Student;
use Illuminate\Http\Request;

class PDController extends Controller
{
    /**
     * Get psychomotor domain creation form
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
        $pdTypes = PDType::all();

        $period = is_null($periodSlug)
            ? Period::activePeriod()
            : Period::where('slug', $periodSlug)->firstOrFail();

        //get student pds for period and term passed into the controller
        $studentPDs = $student->pds()->where('period_id', $period->id);
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

        return view('createPD', compact('pdTypes', 'student', 'pdTypesValues', 'period'));
    }

    /**
     * Store or update PD record
     * 
     * If the optional periodSlug parameter is null, it
     * uses the active period to store the pdType else it
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
            'pdTypes.*' => ['required', 'numeric', 'min:1', 'max:5'],
        ]);

        $period = is_null($periodSlug) ?
            period::activePeriod() : Period::where('slug', $periodSlug)->firstOrFail();

        foreach ($validatedData['pdTypes'] as $pdType => $value) {
            $pdType = PDType::where('slug', $pdType)->first();
            PD::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'period_id' => $period->id,
                    'p_d_type_id' => $pdType->id,
                ],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Record Added');
    }
}
