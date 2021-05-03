<?php

namespace App\Http\Controllers;

use App\Models\ADType;
use App\Models\PDType;
use App\Models\Period;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;
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

        if (is_null($activePeriod)) {
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
        $period = Period::where('slug', $periodSlug)->firstOrFail();

        $pdTypes = PDType::all();
        $pds = $this->getPds($student, $period);

        $adTypes = ADType::all();
        $ads = $this->getAds($student, $period);

        //Get the subjects for the student's class in the selected period Academic Session
        $subjects = $student->classroom->subjects()->where('academic_session_id',  $period->academicSession->id)->get();

        //Check if the class has subjects
        if (count($subjects) < 1) {
            return redirect()->route('classroom.show', ['classroom' => $student->classroom])->with('error', 'The student\'s class does not have subjects set for the selected academic session');
        }

        $results = [];

        //create a results array from all subjects from the student's class
        foreach ($subjects as $subject) {
            $result = Result::where('student_id', $student->id)
                ->where('period_id', $period->term->id)->where('subject_id', $subject->id)->first();

            $result = [$subject->name => $result];
            $results = array_merge($results, $result);
        }

        $maxScores = [];
        $minScores = [];
        $averageScores = [];
        $totalObtained = 0;
        $totalObtainable = count($subjects) * 100;
        $currentDate = now()->year;
        $yearOfBirth = Carbon::createFromFormat('Y-m-d', $student->date_of_birth)->format('Y');
        $age = $currentDate - $yearOfBirth;
        $numberOfTimesPresent = $student->attendances()->where('period_id', $period->id)->first();

        //Get class score statistics
        foreach ($results as $key => $result) {

            //if student does not have a result recorded for the subject
            if ($result == null) {
                $maxScore = [$key => null];
                $maxScores = array_merge($maxScores, $maxScore);

                $minScore = [$key => null];
                $minScores = array_merge($minScores, $minScore);

                $averageScore = [$key => null];
                $averageScores = array_merge($averageScores, $averageScore);
            } else {
                $scoresQuery = Result::where('period_id', $period->id)->where('subject_id', $result->subject->id);

                //highest scores
                $maxScore = $scoresQuery->max('total');

                $maxScore = [$key => $maxScore];
                $maxScores = array_merge($maxScores, $maxScore);

                //Lowest scores
                $minScore = $scoresQuery->min('total');

                $minScore = [$key => $minScore];
                $minScores = array_merge($minScores, $minScore);

                //Average Scores
                $averageScore = $scoresQuery->pluck('total');
                $averageScore = collect($averageScore)->avg();
                $averageScore = [$key => $averageScore];
                $averageScores = array_merge($averageScores, $averageScore);

                //total obtained score
                $totalObtained += $result->total;
            }
        }

        $percentage = $totalObtained / $totalObtainable * 100;

        return view('performanceReport', compact(
            'student',
            'totalObtained',
            'totalObtainable',
            'percentage',
            'results',
            'maxScores',
            'averageScores',
            'minScores',
            'age',
            'pds',
            'pdTypes',
            'ads',
            'adTypes',
            'numberOfTimesPresent',
            'period'
        ));
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

    /**
     * Get Pychomotor domains for a given period
     *
     * @param  Student $student
     * @param  Period $period
     * @return array
     */
    private function getPds($student, $period)
    {
        // get pds for the period
        $pds = $student->pds()->where('period_id', $period->id)->get();

        $pdTypeIds = [];
        $values = [];

        //for each of the pds push the pdTypeId and pd value into two separate arrays
        foreach ($pds as $pd) {
            $pdTypeId = $pd->p_d_type_id;
            $value = $pd->value;
            array_push($pdTypeIds, $pdTypeId);
            array_push($values, $value);
        }

        //for each pdTypeId get the name and push it into an array
        $pdTypeNames = [];
        foreach ($pdTypeIds as $pdTypeId) {
            $pdTypeName = PDType::find($pdTypeId)->name;
            array_push($pdTypeNames, $pdTypeName);
        }

        //comnine the values array and the names array to form a new associative pds array
        $pds = array_combine($pdTypeNames, $values);

        return $pds;
    }

    /**
     * Get Affective domains for given period
     *
     * @param  Student $student
     * @param  Period $period
     * @return array
     */
    private function getAds($student, $period)
    {
        // get ads for period
        $ads = $student->ads()->where('period_id', $period->id)->get();

        $adTypeIds = [];
        $values = [];

        //for each of the ads push the adTypeId and pd value into two separate arrays
        foreach ($ads as $ad) {
            $adTypeId = $ad->a_d_type_id;
            $value = $ad->value;
            array_push($adTypeIds, $adTypeId);
            array_push($values, $value);
        }

        //for each adTypeId get the name and push it into an array
        $adTypeNames = [];
        foreach ($adTypeIds as $adTypeId) {
            $adTypeName = ADType::find($adTypeId)->name;
            array_push($adTypeNames, $adTypeName);
        }

        //comnine the values array and the names array to form a new associative ads array
        $ads = array_combine($adTypeNames, $values);

        return $ads;
    }
}
