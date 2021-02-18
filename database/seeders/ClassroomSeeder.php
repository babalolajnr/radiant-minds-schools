<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Subject;
use Database\Factories\ClassroomFactory;
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
        $classes = ClassroomFactory::$classes;
        Classroom::factory()->times(count($classes))->create();
    }
}
