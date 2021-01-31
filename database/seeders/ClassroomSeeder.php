<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = [
            'Pre nursery',
            'Nursery 1',
            'Nursery 2',
            'Reception',
            'Grade 1',
            'Grade 2',
            'Grade 3',
            'Grade 4',
            'Grade 5',
        ];

        $subject = Subject::inRandomOrder()->first();

        if (!$subject) {
            Artisan::call('db:seed', ['--class' => 'SubjectSeeder']);
            $subject = Subject::inRandomOrder()->limit(5)->get()->pluck('id');
        } else {
            $subject = Subject::inRandomOrder()->limit(5)->get()->pluck('id');
        }

        foreach ($classes as $class) {
            $classroom = Classroom::create(
                [
                    'name' => $class,
                    'teacher_id' => Teacher::factory()->create(['status' => 'active'])->id
                ]
            );

            $classroom->subjects()->sync($subject);
        }
    }
}
