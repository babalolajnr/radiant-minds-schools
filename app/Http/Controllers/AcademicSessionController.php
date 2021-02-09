<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use Illuminate\Http\Request;

class AcademicSessionController extends Controller
{
    public function index()
    {
        $academicSessions = AcademicSession::all();
        return view('academicSession', compact('academicSessions'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'This field is required',
            'name.unique' => 'Record exists'
        ];

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'unique:academic_sessions']
        ], $messages);

        AcademicSession::create($validatedData);
        return back()->with('success', 'Academic Session Created!');
    }

    public function edit($id)
    {
        $academicSession = AcademicSession::findOrFail($id);
        return view('editAcademicSession', compact('academicSession'));
    }

    public function update($id, Request $request)
    {
        $messages = [
            'name.required' => 'This field is required',
            'name.unique' => 'Record exists'
        ];

        $academicSession = AcademicSession::findOrFail($id);

        $academicSession->update($this->validate($request, [
            'name' => ['required', 'string', 'unique:academic_sessions']
        ], $messages));

        return redirect('/academicSessions')->with('success', 'Academic Session Updated!');
    }

    public function destroy($id, AcademicSession $academicSession)
    {
        $this->authorize('delete', $academicSession);
        $academicSession = AcademicSession::findOrFail($id);
        $results = $academicSession->results()->first();

        if (!is_null($results)) {
            return back()->with('error', 'Academic Session with results cannot be deleted!');
        }

        $academicSession->delete();
        return back()->with('success', 'Academic Session Deleted!');
    }
}
