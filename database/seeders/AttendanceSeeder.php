<?php

namespace Database\Seeders;

use App\Models\Period;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class AttendanceSeeder extends Seeder
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

            foreach ($data['periods'] as $period) {

                $record = Attendance::where('student_id', $student->id)->where('period_id', $period->id);

                if ($record->exists()) {
                    continue;
                }

                Attendance::create([
                    'period_id' => $period->id,
                    'student_id' => $student->id,
                    'value' => mt_rand(1, 100),
                ]);
            }

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }

    private function allRecords()
    {
        $period = Period::first();
        $student = Student::first();


        //if any of the required values are empty seed their tables

        if (is_null($period)) {
            Artisan::call('db:seed', ['--class' => 'PeriodSeeder']);
        }

        if (is_null($student)) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
        }

        $periods = Period::all();
        $students = Student::all();

        return [
            'periods' => $periods,
            'students' => $students,
        ];
    }
}
