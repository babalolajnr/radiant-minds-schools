<?php

namespace Database\Seeders;

use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Period;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $results = count(Result::all());
        $seedNumber = 2000;

        if ($results < $seedNumber) {
            $allRecords = $this->allRecords();

            //generate 2000 random results
            for ($i = 0; $i < ($seedNumber - $results); $i++) {
                $values = $this->getRandomValues($allRecords);

                //get record where subject_id,period_id and student_id exists 
                $record = Result::where('subject_id', $values['subject']->id)
                    ->where('student_id', $values['student']->id)
                    ->where('period_id', $values['period']->id);

                while ($record->exists()) {
                    $values = $this->getRandomValues($allRecords);

                    $record = Result::where('subject_id', $values['subject']->id)
                        ->where('student_id', $values['student']->id)
                        ->where('period_id', $values['period']->id);
                }

                $ca = mt_rand(0, 40);
                $exam = mt_rand(0, 60);

                Result::create([
                    'period_id' => $values['period']->id,
                    'subject_id' => $values['subject']->id,
                    'student_id' => $values['student']->id,
                    'ca' => $ca,
                    'exam' => $exam,
                    'total' => $exam + $ca
                ]);
            }
        }
    }

    private function allRecords()
    {
        $period = Period::first();
        $student = Student::first();
        $subject = Subject::first();

        //if any of the required values are empty seed their tables
        if (is_null($period)) {
            Artisan::call('db:seed', ['--class' => 'PeriodSeeder']);
        }

        if (is_null($subject)) {
            Artisan::call('db:seed', ['--class' => 'SubjectSeeder']);
        }

        if (is_null($student)) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
        }

        $periods = Period::all();
        $students = Student::all();
        $subjects = Subject::all();

        return [
            'periods' => $periods,
            'students' => $students,
            'subjects' => $subjects
        ];
    }

    private function getRandomValues($allRecords)
    {
        $student = $allRecords['students']->random();
        $period = $allRecords['periods']->random();
        $subject = $allRecords['subjects']->random();

        return [
            'student' => $student,
            'period' => $period,
            'subject' =>  $subject
        ];
    }
}
