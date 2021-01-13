<?php

namespace Database\Factories;

use App\Models\AssessmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssessmentType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $assessmentTypes = [
            ['name' => 'C.A', 'max_score' => 40],
            ['name' => 'Exam', 'max_score' => 100],
        ];

        $assessmentType = $this->faker->randomElement($assessmentTypes);

        return [
            'name' => $assessmentType['name'],
            'max_score' => $assessmentType['max_score']
        ];
    }
}
