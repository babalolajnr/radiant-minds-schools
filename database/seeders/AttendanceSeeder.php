<?php

namespace Database\Seeders;

use App\Models\AcademicSessionTerm;
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
        $attendances = count(Attendance::all());
        $seedNumber = 2000;
        if ($attendances < $seedNumber) {
            $allRecords = $this->allRecords();

            //generate 2000 random results
            for ($i = 0; $i < ($seedNumber - $attendances); $i++) {
                $values = $this->getRandomValues($allRecords);

                //get record where student_id and academic_session_term_id exists 
                $record = Attendance::where('student_id', $values['student']->id)
                    ->where('academic_session_term_id', $values['period']->id);

                while ($record->exists()) {
                    $values = $this->getRandomValues($allRecords);

                    $record = Attendance::where('student_id', $values['student']->id)
                        ->where('academic_session_term_id', $values['period']->id);
                }

                $value = mt_rand(1, 100);
                Attendance::create([
                    'academic_session_term_id' => $values['period']->id,
                    'student_id' => $values['student']->id,
                    'value' => $value,
                ]);
            }
        }
    }

    private function allRecords()
    {
        $period = AcademicSessionTerm::first();
        $student = Student::first();


        //if any of the required values are empty seed their tables

        if (is_null($period)) {
            Artisan::call('db:seed', ['--class' => 'AcademicSessionTermSeeder']);
        }

        if (is_null($student)) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
        }

        $periods = AcademicSessionTerm::all();
        $students = Student::all();

        return [
            'periods' => $periods,
            'students' => $students,
        ];
    }

    private function getRandomValues($allRecords)
    {
        $student = $allRecords['students']->random();
        $period = $allRecords['periods']->random();

        return [
            'student' => $student,
            'period' => $period,
        ];
    }
}
