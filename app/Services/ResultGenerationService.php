<?php

namespace App\Services;

use App\Models\ADType;
use App\Models\PDType;
use App\Models\Period;
use App\Models\Result;
use App\Models\Student;
use Carbon\Carbon;
use Exception;

/**
 * Service class for result generation
 */
class ResultGenerationService
{

    private $student;

    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * generate Report
     *
     * @param  mixed $periodSlug
     * @return array
     */
    public function generateReport($periodSlug)
    {
        $period = Period::where('slug', $periodSlug)->firstOrFail();

        $pdTypes = PDType::all();
        $pds = $this->getPds($this->student, $period);

        $adTypes = ADType::all();
        $ads = $this->getAds($this->student, $period);

        //Get the subjects for the student's class in the selected period Academic Session
        $subjects = $this->student->classroom->subjects()->where('academic_session_id',  $period->academicSession->id)->get();

        /**
         * Check if the class has subjects
         * Classroom's academic session subjects are needed to generate report
         */
        if (count($subjects) < 1) {
            throw new Exception("Student's class does not have subjects");
        }

        $results = [];

        //create a results array from all subjects from the student's class
        foreach ($subjects as $subject) {
            $result = Result::where('student_id', $this->student->id)
                ->where('period_id', $period->id)->where('subject_id', $subject->id)->first();

            $result = [$subject->name => $result];
            $results = array_merge($results, $result);
        }

        $maxScores = [];
        $minScores = [];
        $averageScores = [];
        $totalObtained = 0;
        $totalObtainable = count($subjects) * 100;
        $currentDate = now()->year;
        $yearOfBirth = Carbon::createFromFormat('Y-m-d', $this->student->date_of_birth)->format('Y');
        $age = $currentDate - $yearOfBirth;
        $numberOfTimesPresent = $this->student->attendances()->where('period_id', $period->id)->first();

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

        return [
            'student' => $this->student,
            'totalObtained' => $totalObtained,
            'totalObtainable' => $totalObtainable,
            'percentage' => $percentage,
            'results' => $results,
            'maxScores' => $maxScores,
            'averageScores' => $averageScores,
            'minScores' => $minScores,
            'age' => $age,
            'pds' => $pds,
            'pdTypes' => $pdTypes,
            'ads' => $ads,
            'adTypes' => $adTypes,
            'numberOfTimesPresent' => $numberOfTimesPresent,
            'period' => $period,
        ];
    }
    /**
     * Get Pychomotor domains for a given period
     *
     * @param  Period $period
     * @return array
     */
    private function getPds($period)
    {
        // get pds for the period
        $pds = $this->student->pds()->where('period_id', $period->id)->get();

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
     * @param  Period $period
     * @return array
     */
    private function getAds($period)
    {
        // get ads for period
        $ads = $this->student->ads()->where('period_id', $period->id)->get();

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