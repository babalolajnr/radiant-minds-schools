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
        return [
            'term_id' => Term::factory()->create()->id,
            'assessment_type_id' => AssessmentType::factory()->create()->id,
            'academic_session_id' => AcademicSession::factory()->create()->id,
        ];
    }
}
