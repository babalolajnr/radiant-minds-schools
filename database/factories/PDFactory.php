<?php

namespace Database\Factories;

use App\Models\PD;
use App\Models\PDType;
use App\Models\Period;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class PDFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PD::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'period_id' => Period::factory()->create()->id,
            'student_id' => Student::factory()->create()->id,
            'value' => mt_rand(1, 5),
            'p_d_type_id' => PDType::factory()->create()->id,
        ];
    }
}
