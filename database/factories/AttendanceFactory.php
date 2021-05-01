<?php

namespace Database\Factories;

use App\Models\Period;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'student_id' => Student::factory()->create()->id,
            'value' => mt_rand(1, 100),
            'period_id' => Period::factory()->create()->id,
        ];
    }
}
