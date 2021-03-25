<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
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

                //get record where subject_id,term_id,student_id and academic_session_id exists 
                $record = Result::where('subject_id', $values['subject']->id)
                    ->where('student_id', $values['student']->id)
                    ->where('term_id', $values['term']->id)
                    ->where('academic_session_id', $values['academicSession']->id);

                while ($record->exists()) {
                    $values = $this->getRandomValues($allRecords);

                    $record = Result::where('subject_id', $values['subject']->id)
                        ->where('student_id', $values['student']->id)
                        ->where('term_id', $values['term']->id)
                        ->where('academic_session_id', $values['academicSession']->id);
                }

                $ca = mt_rand(0, 40);
                $exam = mt_rand(0, 60);

                Result::create([
                    'term_id' => $values['term']->id,
                    'academic_session_id' => $values['academicSession']->id,
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
        $term = Term::first();
        $academicSession = AcademicSession::first();
        $student = Student::first();
        $subject = Subject::first();

        //if any of the required values are empty seed their tables
        if (is_null($term)) {
            Artisan::call('db:seed', ['--class' => 'TermSeeder']);
        }

        if (is_null($academicSession)) {
            Artisan::call('db:seed', ['--class' => 'AcademicSessionSeeder']);
        }

        if (is_null($subject)) {
            Artisan::call('db:seed', ['--class' => 'SubjectSeeder']);
        }

        if (is_null($student)) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
        }

        $terms = Term::all();
        $academicSessions = AcademicSession::all();
        $students = Student::all();
        $subjects = Subject::all();

        return [
            'terms' => $terms,
            'academicSessions' => $academicSessions,
            'students' => $students,
            'subjects' => $subjects
        ];
    }

    private function getRandomValues($allRecords)
    {
        $student = $allRecords['students']->random();
        $term = $allRecords['terms']->random();
        $subject = $allRecords['subjects']->random();
        $academicSession = $allRecords['academicSessions']->random();

        return [
            'student' => $student,
            'term' => $term,
            'academicSession' => $academicSession,
            'subject' =>  $subject
        ];
    }
}
