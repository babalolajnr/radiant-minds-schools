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

        $this->validate($request, [
            'name' => ['required', 'string', 'unique:academic_sessions']
        ], $messages);

        AcademicSession::create($request->all());
        return back()->with('success', 'Academic Session Created!');
    }

    public function edit($id)
    {
        $academicSession = AcademicSession::findOrFail($id);
        return response(200);
    }

    public function update($id, Request $request)
    {
        $academicSession = AcademicSession::findOrFail($id);
        $academicSession->update($request->all());
        return response(200);
    }

    public function destroy($id, AcademicSession $academicSession)
    {
        $this->authorize('delete', $academicSession);
        $academicSession = AcademicSession::findOrFail($id);
        $academicSession->delete();
        return back()->with('success', 'Academic Session Deleted!');
    }
}
