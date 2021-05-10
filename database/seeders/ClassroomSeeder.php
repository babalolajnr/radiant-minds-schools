<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Teacher;
use Database\Factories\ClassroomFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classrooms = ClassroomFactory::$classes;
        foreach ($classrooms as $classroom) {

            $row = Classroom::where('name', $classroom['name']);
            if ($row->exists()) {
                continue;
            }

            Classroom::create(
                [
                    'name' => $classroom['name'],
                    'rank' => $classroom['rank'],
                    'slug' => Str::of($classroom['name'])->slug('-'),
                    'teacher_id' => Teacher::factory()->create(['is_active' => true])->id
                ]
            );
        }
    }
}
