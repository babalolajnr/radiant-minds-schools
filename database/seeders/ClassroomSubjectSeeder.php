<?php

namespace Database\Seeders;

use App\Models\ClassroomSubject;
use Illuminate\Database\Seeder;

class ClassroomSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClassroomSubject::factory()->times('10')->create();
    }
}
