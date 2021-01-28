<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Models\Subject;
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
        $assessment = $values['assessment'];
        $maxScore = $assessment->assessmentType->max_score;
        $mark = mt_rand(0, $maxScore);

        return [
            'assessment_id' => $assessment->id,
            'subject_id' => $values['subject']->id,
            'student_id' => $values['student']->id,
            'mark' => $mark
        ];
    }

    private function generateValues()
    {
        $assessment = Assessment::inRandomOrder()->first();
        $subject = Subject::inRandomOrder()->first();
        $student = Student::inRandomOrder()->first();

        if (!$assessment) {
            Artisan::call('db:seed', ['--class' => 'AssessmentSeeder']);
            $assessment = Assessment::inRandomOrder()->first();
        }

        if (!$subject) {
            Artisan::call('db:seed', ['--class' => 'SubjectSeeder']);
            $subject = Subject::inRandomOrder()->first();
        }

        if (!$student) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
            $student = Assessment::inRandomOrder()->first();
        }

        return ['assessment' => $assessment, 'subject' => $subject, 'student' => $student];

    }
}
