<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Student $student, $termSlug, $academicSessionName = null)
    {
        $term = Term::where('slug', $termSlug)->firstOrFail();
        $academicSession = is_null($academicSessionName) ? AcademicSession::currentAcademicSession() : AcademicSession::where('name', $academicSessionName)->firstOrFail();
        $attendance = $student->attendances()->where('academic_session_id', $academicSession->id)->where('term_id', $term->id);

        if ($attendance->exists()) {
            $attendance = $attendance->first();
            return view('createAttendance', compact('attendance', 'academicSession', 'student', 'term'));
        }

        return view('createAttendance', compact('academicSession', 'student', 'term'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Student $student, $termId, $academicSessionId = null)
    {
        $term = Term::findOrFail($termId);

        if (!is_null($academicSessionId)) {
            $academicSession = AcademicSession::findOrFail($academicSessionId);
        } else {
            $academicSession = AcademicSession::currentAcademicSession();
        }

        $data = $request->validate([
            'value' => ['required', 'numeric']
        ]);

        $student->attendances()->updateOrCreate([
            'academic_session_id' => $academicSession->id,
            'term_id' => $term->id
        ], ['value' => $data['value']]);

        return back()->with('success', 'Record Added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
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
