<?php

namespace Database\Seeders;

use App\Models\PD;
use App\Models\PDType;
use App\Models\Student;
use App\Models\Period;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pds = count(PD::all());
        $seedNumber = 2000;

        if ($pds < $seedNumber) {
            $allRecords = $this->allRecords();

            //generate 2000 random results
            for ($i = 0; $i < ($seedNumber - $pds); $i++) {
                $values = $this->getRandomValues($allRecords);

                //get record where subject_id,period_id,student_id exists 
                $record = PD::where('p_d_type_id', $values['pdType']->id)
                    ->where('student_id', $values['student']->id)
                    ->where('period_id', $values['period']->id);

                while ($record->exists()) {
                    $values = $this->getRandomValues($allRecords);

                    $record = PD::where('p_d_type_id', $values['pdType']->id)
                        ->where('student_id', $values['student']->id)
                        ->where('period_id', $values['period']->id);
                }

                PD::create([
                    'period_id' => $values['period']->id,
                    'student_id' => $values['student']->id,
                    'value' => mt_rand(1, 5),
                    'p_d_type_id' => $values['pdType']->id
                ]);
            }
        }
    }

    private function allRecords()
    {
        $period = Period::first();
        $student = Student::first();
        $pdType = PDType::first();

        //if any of the required values are empty seed their tables
        if (is_null($period)) {
            Artisan::call('db:seed', ['--class' => 'TermSeeder']);
        }

        if (is_null($student)) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
        }

        if (is_null($pdType)) {
            Artisan::call('db:seed', ['--class' => 'PDTypeSeeder']);
        }

        $periods = Period::all();
        $students = Student::all();
        $pdTypes = PDType::all();

        return [
            'periods' => $periods,
            'students' => $students,
            'pdTypes' => $pdTypes
        ];
    }

    private function getRandomValues($allRecords)
    {
        $student = $allRecords['students']->random();
        $period = $allRecords['periods']->random();
        $pdType = $allRecords['pdTypes']->random();

        return [
            'student' => $student,
            'period' => $period,
            'pdType' => $pdType
        ];
    }
}
