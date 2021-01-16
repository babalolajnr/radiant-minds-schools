<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Assessment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $term = Term::factory()->create();
        $assessmentType = AssessmentType::factory()->create();
        $academicSession = AcademicSession::factory()->create();
        $name = $assessmentType->name . ' ' . $term->name . ' ' . $academicSession->name;
        return [
            'term_id' => $term->id,
            'assessment_type_id' => $assessmentType->id,
            'academic_session_id' => $academicSession->id,
            'name' => $name
        ];
    }
}
