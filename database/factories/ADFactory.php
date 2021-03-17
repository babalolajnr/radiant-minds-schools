<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use App\Models\AD;
use App\Models\ADType;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class ADFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AD::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'term_id' => Term::factory()->create()->id,
            'academic_session_id' => AcademicSession::factory()->create()->id,
            'student_id' => Student::factory()->create()->id,
            'value' => mt_rand(1, 5),
            'a_d_type_id' => ADType::factory()->create()->id,
        ];
    }
}
