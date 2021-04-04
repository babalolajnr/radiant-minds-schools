<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = count(Student::all());
        $seedNumber = 500;
        if ($students < $seedNumber) {
            Student::factory()->times($seedNumber - $students)->create();
        }
    }
}
