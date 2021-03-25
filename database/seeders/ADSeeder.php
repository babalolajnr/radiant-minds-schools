<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\AD;
use App\Models\ADType;
use App\Models\Student;
use App\Models\Term;
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

                //get record where subject_id,term_id,student_id and academic_session_id exists 
                $record = AD::where('a_d_type_id', $values['adType']->id)
                    ->where('student_id', $values['student']->id)
                    ->where('term_id', $values['term']->id)
                    ->where('academic_session_id', $values['academicSession']->id);

                while ($record->exists()) {
                    $values = $this->getRandomValues($allRecords);

                    $record = AD::where('a_d_type_id', $values['adType']->id)
                        ->where('student_id', $values['student']->id)
                        ->where('term_id', $values['term']->id)
                        ->where('academic_session_id', $values['academicSession']->id);
                }

                $value = mt_rand(1, 5);
                AD::create([
                    'term_id' => $values['term']->id,
                    'academic_session_id' => $values['academicSession']->id,
                    'student_id' => $values['student']->id,
                    'value' => $value,
                    'a_d_type_id' => $values['adType']->id
                ]);
            }
        }
    }

    private function allRecords()
    {
        $term = Term::first();
        $academicSession = AcademicSession::first();
        $student = Student::first();
        $adType = ADType::first();

        //if any of the required values are empty seed their tables
        if (is_null($term)) {
            Artisan::call('db:seed', ['--class' => 'TermSeeder']);
        }

        if (is_null($academicSession)) {
            Artisan::call('db:seed', ['--class' => 'AcademicSessionSeeder']);
        }

        if (is_null($student)) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
        }

        if (is_null($adType)) {
            Artisan::call('db:seed', ['--class' => 'ADTypeSeeder']);
        }

        $terms = Term::all();
        $academicSessions = AcademicSession::all();
        $students = Student::all();
        $adTypes = ADType::all();

        return [
            'terms' => $terms,
            'academicSessions' => $academicSessions,
            'students' => $students,
            'adTypes' => $adTypes
        ];
    }

    private function getRandomValues($allRecords)
    {
        $student = $allRecords['students']->random();
        $term = $allRecords['terms']->random();
        $academicSession = $allRecords['academicSessions']->random();
        $adType = $allRecords['adTypes']->random();

        return [
            'student' => $student,
            'term' => $term,
            'academicSession' => $academicSession,
            'adType' => $adType
        ];
    }
}
