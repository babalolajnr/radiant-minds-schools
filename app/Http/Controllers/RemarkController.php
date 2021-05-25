<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Remark;
use App\Models\Student;
use Illuminate\Http\Request;

class RemarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Student $student, $periodSlug = null)
    {
        $period = is_null($periodSlug)
            ? Period::activePeriod()
            : Period::where('slug', $periodSlug)->firstOrFail();

        $remarks = $student->remarks()->where('period_id', $period->id);

        if ($remarks->exists()) {
            $remarks = $remarks->first();
        } else {
            $remarks = null;
        }

        return view('createRemark', compact('period', 'student', 'remarks'));
    }

    /**
     * Store or Update a remark
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Student $student, Request $request, $periodSlug = null)
    {
        $validated = $request->validate([
            'class_teacher_remark' => ['string'],
            'hos_remark' => ['string']
        ]);

        $period = Period::where('slug', $periodSlug)->first();

        Remark::updateOrCreate(
            [
                'student_id' => $student->id,
                'period_id' => $period->id,
            ],
            [
                'class_teacher_remark' => $validated['class_teacher_remark'],
                'hos_remark' => $validated['hos_remark']
            ]
        );

        return back()->with('success', 'Remark recorded!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Remark  $remark
     * @return \Illuminate\Http\Response
     */
    public function show(Remark $remark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Remark  $remark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Remark $remark)
    {
        //
    }
}
