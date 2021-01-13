<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

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

        foreach ($classes as $class) {
            Classroom::create(
                [
                    'name' => $class,
                    'teacher_id' => Teacher::factory()->create(['status' => 'active'])->id
                ]
            );
        }
    }
}
