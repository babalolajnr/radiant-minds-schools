<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teachers = count(Teacher::all());
        $seedNumber = 15;
        if ($teachers < $seedNumber) {
            Teacher::factory()->times($seedNumber - $teachers)->create();
        }
    }
}
