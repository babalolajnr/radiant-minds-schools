<?php

namespace Database\Seeders;

use App\Models\AssessmentType;
use Illuminate\Database\Seeder;

class AssessmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assessmentTypes = [
            ['name' => 'C.A', 'max_score' => 40],
            ['name' => 'Exam', 'max_score' => 100],
        ];

        foreach ($assessmentTypes as $assessmentType) {
            AssessmentType::create([
                'name' => $assessmentType['name'],
                'max_score' => $assessmentType['max_score']
            ]);
        }
    }
}
