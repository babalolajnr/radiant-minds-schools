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
        $ads = count(AD::all());
        $seedNumber = 2000;
        if ($ads < $seedNumber) {
            $allRecords = $this->allRecords();

            //generate 2000 random results
            for ($i = 0; $i < ($seedNumber - $ads); $i++) {
                $values = $this->getRandomValues($allRecords);

                //get record where subject_id,period_id,student_id exists 
                $record = AD::where('a_d_type_id', $values['adType']->id)
                    ->where('student_id', $values['student']->id)
                    ->where('period_id', $values['period']->id);

                while ($record->exists()) {
                    $values = $this->getRandomValues($allRecords);

                    $record = AD::where('a_d_type_id', $values['adType']->id)
                        ->where('student_id', $values['student']->id)
                        ->where('period_id', $values['period']->id);
                }

                $value = mt_rand(1, 5);
                AD::create([
                    'period_id' => $values['period']->id,
                    'student_id' => $values['student']->id,
                    'value' => $value,
                    'a_d_type_id' => $values['adType']->id
                ]);
            }
        }
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
