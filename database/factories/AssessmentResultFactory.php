<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $assessment = Assessment::factory()->create();
        $maxScore = $assessment->assessmentType->max_score;
        $mark = mt_rand(0, $maxScore);
        
        return [
            'assessment_id' => $assessment->id,
            'subject_id' => Subject::factory()->create()->id,
            'student_id' => Student::factory()->create()->id,
            'mark' => $mark
        ];
    }
}
