<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicSessionController extends Controller
{
    use ValidationTrait;

    private function validateAcademicSession($request, $academicSession = null)
    {
        $messages = [
            'name.required' => 'This field is required',
            'name.unique' => 'Record exists'
        ];

        $validatedData = $request->validate([
            'name' => ['required', 'string', Rule::unique('academic_sessions')->ignore($academicSession), 'regex:/^\d{4}[-]{1}\d{4}$/m'],
            'start_date' => ['required', 'date', Rule::unique('academic_sessions')->ignore($academicSession)],
            'end_date' => ['required', 'date', Rule::unique('academic_sessions')->ignore($academicSession), 'after:start_date']
        ], $messages);

        return $validatedData;
    }

    public function index()
    {
        $academicSessions = AcademicSession::all();
        return view('academicSession', compact('academicSessions'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAcademicSession($request);

        //check if date range is unique
        $validateDateRange = $this->validateDateRange($data['start_date'], $data['end_date'], AcademicSession::class);

        if ($validateDateRange !== true) {
            return back()->with('error', 'Date range overlaps with another period');
        }

        AcademicSession::create($data);

        return back()->with('success', 'Academic Session Created!');
    }

    public function edit(AcademicSession $academicSession)
    {
        return view('editAcademicSession', compact('academicSession'));
    }

    public function update(AcademicSession $academicSession, Request $request)
    {
        $data = $this->validateAcademicSession($request, $academicSession);

        //check if date range is unique
        $validateDateRange = $this->validateDateRange($data['start_date'], $data['end_date'], AcademicSession::class);

        if ($validateDateRange !== true) {
            return back()->with('error', 'Date range overlaps with another period');
        }

        $academicSession->update($data);

        return redirect()->route('academic-session.index')->with('success', 'Academic Session Updated!');
    }

    public function destroy(AcademicSession $academicSession)
    {
        try {
            $academicSession->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                //SQLSTATE[23000]: Integrity constraint violation
                return back()->with('error', 'Academic session can not be deleted because some resources are dependent on it!');
            }
        }
        return back()->with('success', 'Academic Session Deleted!');
    }
}
