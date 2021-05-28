<?php

namespace App\Http\Controllers;

use App\Models\HosRemark;
use App\Models\Period;
use App\Models\Student;
use Illuminate\Http\Request;

class HosRemarkController extends Controller
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
     * @param Student $student
     * @return \Illuminate\Http\Response
     */
    public function create(Student $student)
    {
        $period = Period::activePeriod();

        if (!Period::activePeriodIsSet()) {
            return back()->with('error', 'Active Period is not set');
        };

        $remark = $student->hosRemarks()->where('period_id', $period->id);

        if ($remark->exists()) {
            $remark = $remark->first();
        } else {
            $remark = null;
        }

        return view('createHosRemark', compact('period', 'student', 'remark'));
    }

    /**
     * Store or update Hos remark
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Student $student, Request $request)
    {
        $validated = $request->validate([
            'remark' => ['string', 'required']
        ]);

        if (!Period::activePeriodIsSet()) {
            return back()->with('error', 'Active Period is not set');
        };

        HosRemark::updateOrCreate(
            [
                'student_id' => $student->id,
                'period_id' => Period::activePeriod()->id,
            ],
            [
                'user_id' => $request->user()->id,
                'remark' => $validated['remark'],
            ]
        );

        return back()->with('success', 'HOS Remark recorded!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HosRemark  $hosRemark
     * @return \Illuminate\Http\Response
     */
    public function show(HosRemark $hosRemark)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HosRemark  $hosRemark
     * @return \Illuminate\Http\Response
     */
    public function edit(HosRemark $hosRemark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HosRemark  $hosRemark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HosRemark $hosRemark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HosRemark  $hosRemark
     * @return \Illuminate\Http\Response
     */
    public function destroy(HosRemark $hosRemark)
    {
        //
    }
}
