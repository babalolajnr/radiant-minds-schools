<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\ClassroomSubject;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ClassroomSubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClassroomSubject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $values = $this->generateValues();
        $classroomId = $values['classroom']->id;
        $subjectId = $values['subject']->id;

        $classroomSubject = DB::table('classroom_subject')->where('subject_id', $subjectId)->where('classroom_id', $classroomId);
        while ($classroomSubject->exists()) {
            $values = $this->generateValues();
            $classroomId = $values['classroom']->id;
            $subjectId = $values['subject']->id;
        }

        return [
            'classroom_id' => $classroomId,
            'subject_id' => $subjectId,
        ];
    }

    private function generateValues()
    {
        $classroom = Classroom::inRandomOrder()->first();
        $subject = Subject::inRandomOrder()->first();

        if (!$classroom) {
            Artisan::call('db:seed', ['--class' => 'ClassroomSeeder']);
            $classroom = Classroom::inRandomOrder()->first();
        }

        if (!$subject) {
            Artisan::call('db:seed', ['--class' => 'SubjectSeeder']);
            $subject = Subject::inRandomOrder()->first();
        }

        return ['classroom' => $classroom, 'subject' => $subject];
    }
}
