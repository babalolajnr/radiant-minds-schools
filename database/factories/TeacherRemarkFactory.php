<?php

namespace Database\Factories;

use App\Models\Period;
use App\Models\TeacherRemark;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherRemarkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeacherRemark::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $student = Student::factory()->create();

        return [
            'remark' => $this->faker->realText(),
            'student_id' => $student->id,
            'period_id' => Period::factory()->create(['active' => true])->id,
            'teacher_id' => $student->classroom->teacher->id,
        ];
    }
}
