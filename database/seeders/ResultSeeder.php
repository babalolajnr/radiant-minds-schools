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
        // $this->command->getOutput()->progressStart(100);

        $data = $this->allRecords();

        foreach ($data['students'] as $student) {

            foreach ($data['subjects'] as $subject) {

                foreach ($data['periods'] as $period) {

                    $record = Result::where('subject_id', $subject->id)
                        ->where('student_id', $student->id)
                        ->where('period_id', $period->id);

                    if ($record->exists()) {
                        continue;
                    }

                    $ca = mt_rand(0, 40);
                    $exam = mt_rand(0, 60);
                    Result::create([
                        'period_id' => $period->id,
                        'subject_id' => $subject->id,
                        'student_id' => $student->id,
                        'ca' => $ca,
                        'exam' => $exam,
                        'total' => $exam + $ca
                    ]);
                    // $this->command->getOutput()->progressAdvance();
                }
            }
        }
        Artisan::call('db:seed', ['--class' => 'ClassroomSubjectSeeder']);

        // $this->command->getOutput()->progressFinish();
    }

    /**
     * Query Periods, Students and subjects data
     *
     * @return array
     */
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
