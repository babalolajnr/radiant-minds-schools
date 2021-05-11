<?php

namespace Database\Seeders;

use App\Models\AD;
use App\Models\ADType;
use App\Models\Student;
use App\Models\Period;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ADSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  
        $this->command->getOutput()->progressStart(100);

        $data = $this->allRecords();

        foreach ($data['students'] as $student) {

            foreach ($data['adTypes'] as $adType) {

                foreach ($data['periods'] as $period) {

                    $record = AD::where('a_d_type_id', $adType->id)
                        ->where('student_id', $student->id)
                        ->where('period_id', $period->id);

                    if ($record->exists()) {
                        continue;
                    }

                    AD::create([
                        'period_id' => $period->id,
                        'student_id' => $student->id,
                        'value' => mt_rand(1, 5),
                        'a_d_type_id' => $adType->id
                    ]);
                }
            }
            
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }

    private function allRecords()
    {
        $period = Period::first();
        $student = Student::first();
        $adType = ADType::first();

        //if any of the required values are empty seed their tables
        if (is_null($period)) {
            Artisan::call('db:seed', ['--class' => 'PeriodSeeder']);
        }

        if (is_null($student)) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
        }

        if (is_null($adType)) {
            Artisan::call('db:seed', ['--class' => 'ADTypeSeeder']);
        }

        $periods = Period::all();
        $students = Student::all();
        $adTypes = ADType::all();

        return [
            'periods' => $periods,
            'students' => $students,
            'adTypes' => $adTypes
        ];
    }

    private function getRandomValues($allRecords)
    {
        $student = $allRecords['students']->random();
        $period = $allRecords['periods']->random();
        $adType = $allRecords['adTypes']->random();

        return [
            'student' => $student,
            'period' => $period,
            'adType' => $adType
        ];
    }
}
