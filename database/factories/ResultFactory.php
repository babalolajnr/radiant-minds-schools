<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;

class ResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Result::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $values = $this->generateValues();
        $ca = mt_rand(0, 40);
        $exam = mt_rand(0, 40);

        return [
            'term_id' => $values['term']->id,
            'academic_session_id' => $values['academicSession']->id,
            'subject_id' => $values['subject']->id,
            'student_id' => $values['student']->id,
            'ca' => $ca,
            'exam' => $exam
        ];
    }

    private function generateValues()
    {
        $term = Term::inRandomOrder()->first();
        $academicSession = AcademicSession::inRandomOrder()->first();
        $subject = Subject::inRandomOrder()->first();
        $student = Student::inRandomOrder()->first();

        //if any of the required values are empty seed their tables
        if (!$term) {
            Artisan::call('db:seed', ['--class' => 'TermSeeder']);
            $term = Term::inRandomOrder()->first();
        }

        if (!$academicSession) {
            Artisan::call('db:seed', ['--class' => 'AcademicSessionSeeder']);
            $academicSession = AcademicSession::inRandomOrder()->first();
        }

        if (!$subject) {
            Artisan::call('db:seed', ['--class' => 'SubjectSeeder']);
            $subject = Subject::inRandomOrder()->first();
        }

        if (!$student) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
            $student = Student::inRandomOrder()->first();
        }

        return [
            'term' => $term, 
            'academicSession' => $academicSession, 
            'subject' => $subject, 
            'student' => $student
        ];

    }
}
