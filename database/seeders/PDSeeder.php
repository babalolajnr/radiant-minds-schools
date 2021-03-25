<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\PD;
use App\Models\PDType;
use App\Models\Student;
use App\Models\Term;
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

                //get record where subject_id,term_id,student_id and academic_session_id exists 
                $record = PD::where('p_d_type_id', $values['pdType']->id)
                    ->where('student_id', $values['student']->id)
                    ->where('term_id', $values['term']->id)
                    ->where('academic_session_id', $values['academicSession']->id);

                while ($record->exists()) {
                    $values = $this->getRandomValues($allRecords);

                    $record = PD::where('p_d_type_id', $values['pdType']->id)
                        ->where('student_id', $values['student']->id)
                        ->where('term_id', $values['term']->id)
                        ->where('academic_session_id', $values['academicSession']->id);
                }

                PD::create([
                    'term_id' => $values['term']->id,
                    'academic_session_id' => $values['academicSession']->id,
                    'student_id' => $values['student']->id,
                    'value' => mt_rand(1, 5),
                    'p_d_type_id' => $values['pdType']->id
                ]);
            }
        }
    }

    private function allRecords()
    {
        $term = Term::first();
        $academicSession = AcademicSession::first();
        $student = Student::first();
        $pdType = PDType::first();

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

        if (is_null($pdType)) {
            Artisan::call('db:seed', ['--class' => 'PDTypeSeeder']);
        }

        $terms = Term::all();
        $academicSessions = AcademicSession::all();
        $students = Student::all();
        $pdTypes = PDType::all();

        return [
            'terms' => $terms,
            'academicSessions' => $academicSessions,
            'students' => $students,
            'pdTypes' => $pdTypes
        ];
    }

    private function getRandomValues($allRecords)
    {
        $student = $allRecords['students']->random();
        $term = $allRecords['terms']->random();
        $academicSession = $allRecords['academicSessions']->random();
        $pdType = $allRecords['pdTypes']->random();

        return [
            'student' => $student,
            'term' => $term,
            'academicSession' => $academicSession,
            'pdType' => $pdType
        ];
    }
}
