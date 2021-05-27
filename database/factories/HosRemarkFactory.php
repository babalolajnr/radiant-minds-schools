<?php

namespace Database\Factories;

use App\Models\HosRemark;
use App\Models\Period;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HosRemarkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HosRemark::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'remark' => $this->faker->realText(),
            'student_id' => Student::factory()->create()->id,
            'period_id' => Period::factory()->create(['active' => true])->id,
            'user_id' => User::factory()->create()->id,
        ];
    }
}
