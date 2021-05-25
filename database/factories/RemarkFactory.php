<?php

namespace Database\Factories;

use App\Models\Period;
use App\Models\Remark;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class RemarkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Remark::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'class_teacher_remark' => $this->faker->realText(),
            'hos_remark' => $this->faker->realText(),
            'student_id' => Student::factory()->create()->id,
            'period_id' => Period::factory()->create()->id
        ];
    }
}
