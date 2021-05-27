<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\TeacherRemark;
use App\Models\Student;
use Illuminate\Http\Request;

class TeacherRemarkController extends Controller
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
    public function create(Student $student)
    {
        $period = Period::activePeriod();

        if (!Period::activePeriodIsSet()) {
            return back()->with('error', 'Active Period is not set');
        };

        $remark = $student->teacherRemarks()->where('period_id', $period->id);

        if ($remark->exists()) {
            $remark = $remark->first();
        } else {
            $remark = null;
        }

        return view('createTeacherRemark', compact('period', 'student', 'remark'));
    }

    /**
     * Store or Update a remark
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Student $student, Request $request)
    {
        $validated = $request->validate([
            'remark' => ['string']
        ]);

        if (!Period::activePeriodIsSet()) {
            return back()->with('error', 'Active Period is not set');
        };

        TeacherRemark::updateOrCreate(
            [
                'student_id' => $student->id,
                'period_id' => Period::activePeriod()->id,
                'teacher_id' => $request->user()->id,
            ],
            [
                'remark' => $validated['remark'],
            ]
        );

        return back()->with('success', 'Teacher\'s Remark recorded!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeacherRemark  $remark
     * @return \Illuminate\Http\Response
     */
    public function show(TeacherRemark $remark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeacherRemark  $remark
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeacherRemark $remark)
    {
        //
    }
}
