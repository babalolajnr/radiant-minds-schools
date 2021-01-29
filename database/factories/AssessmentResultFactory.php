<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use App\Models\AssessmentResult;
use App\Models\AssessmentType;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;

class AssessmentResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssessmentResult::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $values = $this->generateValues();
        $assessmentType = $values['assessmentType'];
        $maxScore = $assessmentType->max_score;
        $mark = mt_rand(0, $maxScore);

        return [
            'term_id' => $values['term']->id,
            'assessment_type_id' => $values['assessmentType']->id,
            'academic_session_id' => $values['academicSession']->id,
            'subject_id' => $values['subject']->id,
            'student_id' => $values['student']->id,
            'mark' => $mark
        ];
    }

    private function generateValues()
    {
        $term = Term::inRandomOrder()->first();
        $assessmentType = AssessmentType::inRandomOrder()->first();
        $academicSession = AcademicSession::inRandomOrder()->first();
        $subject = Subject::inRandomOrder()->first();
        $student = Student::inRandomOrder()->first();

        if (!$term) {
            Artisan::call('db:seed', ['--class' => 'TermSeeder']);
            $term = Term::inRandomOrder()->first();
        }

        if (!$assessmentType) {
            Artisan::call('db:seed', ['--class' => 'AssessmentTypeSeeder']);
            $assessmentType = AssessmentType::inRandomOrder()->first();
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
            'assessmentType' => $assessmentType, 
            'term' => $term, 
            'academicSession' => $academicSession, 
            'subject' => $subject, 
            'student' => $student
        ];

    }
}
