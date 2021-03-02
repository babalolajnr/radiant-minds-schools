<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;

class ResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Result::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        $ca = mt_rand(0, 40);
        $exam = mt_rand(0, 60);
        return [
            'term_id' => Term::factory()->create()->id,
            'academic_session_id' => AcademicSession::factory()->create()->id,
            'subject_id' => Subject::factory()->create()->id,
            'student_id' => Student::factory()->create()->id,
            'ca' => $ca,
            'exam' => $exam,
            'total' => $exam + $ca
        ];
    }

}