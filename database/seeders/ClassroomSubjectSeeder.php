<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Subject;
use Database\Factories\ClassroomFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

class ClassroomSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $classrooms = Classroom::all();
        $subject = Subject::inRandomOrder()->first();

        if (empty($classrooms)) {
            Artisan::call('db:seed', ['--class' => 'ClassroomSeeder']);
            $classrooms = Classroom::all();
        }

        if (is_null($subject)) {
            Artisan::call('db:seed', ['--class' => 'SubjectSeeder']);
        }

        $subjects = Subject::pluck('id')->all();

        foreach ($classrooms as $classroom) {
            $syncSubjects = Arr::random($subjects, 9);
            $classroom->subjects()->sync($syncSubjects);
        }

    }
}
