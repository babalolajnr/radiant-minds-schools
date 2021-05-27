<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Services\ResultGenerationService;
use Exception;
use Illuminate\Http\Request;

class ResultController extends Controller
{

    /**
     * Get Result creation page
     *
     * @param  Student $student
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(Student $student)
    {
        $activePeriod = Period::activePeriod();

        if (is_null($activePeriod)) {
            return back()->with('error', 'Active period is not set');
        }

        $subjects = $student->classroom->subjects()->where('academic_session_id',  $activePeriod->academicSession->id)->get();

        return view('createResults', compact('subjects', 'student', 'activePeriod'));
    }

    /**
     * Store student result for active period
     *
     * @param  Request $request
     * @param  Student $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Student $student)
    {

        $messages = [
            'between.ca' => 'The score must be between 0 and 40',
            'between.exam' => 'The score must be between 0 and 60',
        ];

        $validatedData = $request->validate([
            'ca' => ['required', 'numeric', 'between:0,40'],
            'exam' => ['nullable', 'numeric', 'between:0,60'],
            'subject' => ['string', 'required', 'exists:subjects,name']
        ], $messages);

        $subject = Subject::where('name', $validatedData['subject'])->first();

        //term and academic session will be goten from the active period
        $activePeriod = Period::activePeriod();

        if (!Period::activePeriodIsSet()) {
            return back()->with('error', 'Active period is not set');
        }

        $record = Result::where('subject_id', $subject->id)
            ->where('student_id', $student->id)
            ->where('period_id', $activePeriod->id);

        if ($record->exists()) {
            return back()->with('error', 'Record Exists');
        }

        $exam = $validatedData['exam'] ?? 0;
        $ca = $validatedData['ca'] ?? 0;

        Result::create([
            'ca' => $ca,
            'exam' => $exam,
            'period_id' => $activePeriod->id,
            'subject_id' => $subject->id,
            'student_id' => $student->id,
            'total' => $exam + $ca
        ]);

        return back()->with('success', 'Record created! 👍');
    }

    /**
     * Get student performance report
     *
     * @param  Student $student
     * @param  string $periodSlug
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function showPerformanceReport(Student $student, $periodSlug)
    {
        $resultService = new ResultGenerationService($student);

        try {
            $data = $resultService->generateReport($periodSlug);
        } catch (Exception $e) {
            if ($e->getMessage() == "Student's class does not have subjects") {
                return redirect()->route('classroom.show', ['classroom' => $student->classroom])->with('error', 'The student\'s class does not have subjects set for the selected academic session');
            }
        }
        return view('performanceReport', $data);
    }

    /**
     * Get Edit Result Page
     *
     * @param  Result $result
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Result $result)
    {
        //store previous url in session to be used for redirect after update
        session(['resultsPage' => url()->previous()]);
        return view('editResult', compact('result'));
    }

    /**
     * Update result
     *
     * @param  Result $result
     * @param  Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Result $result, Request $request)
    {
        $validatedData = $request->validate([
            'ca' => ['required', 'numeric', 'between:0,40'],
            'exam' => ['nullable', 'numeric', 'between:0,60'],
        ]);
        $exam = $validatedData['exam'] ?? 0;
        $ca = $validatedData['ca'] ?? 0;
        $total = $exam + $ca;
        $total = ['total' => $total];
        $result->update($validatedData + $total);

        //return to previously viewed route b4 edit page
        return redirect($request->session()->get('resultsPage'))->with('success', 'Result Updated!');
    }

    /**
     * Destroy result
     *
     * @param  Result $result
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Result $result)
    {
        $result->delete();
        return back()->with('success', 'Result Deleted');
    }
}
