<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Student $student, $periodSlug = null)
    {
        /**
         * if period is not recieved i.e it's null.
         * The active period should be used 
         */
        $period = is_null($periodSlug)
            ? Period::activePeriod()
            : Period::where('slug', $periodSlug)->firstOrFail();

        $attendance = $student->attendances()->where('period_id', $period->id);

        if ($attendance->exists()) {
            $attendance = $attendance->first();
            return view('createAttendance', compact('attendance', 'period', 'student'));
        }

        return view('createAttendance', compact('period', 'student'));
    }

    /**
     * Store or update attendance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Request $request, Student $student, $periodSlug = null)
    {

        if (!is_null($periodSlug)) {
            $period =  Period::where('slug', $periodSlug)->firstOrFail();
        } else {
            $period = Period::activePeriod();
        }

        if ($period->no_times_school_opened == null) {
            return back()->with('error', 'No of days school opened is null. Contact the admin!');
        }

        $data = $request->validate([
            'value' => ['required', 'numeric', "max:{$period->no_times_school_opened}"]
        ]);

        $student->attendances()->updateOrCreate([
            'period_id' => $period->id,
        ], ['value' => $data['value']]);

        return back()->with('success', 'Record Added!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
