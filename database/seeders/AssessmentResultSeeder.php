<?php

namespace Database\Seeders;

use App\Models\AssessmentResult;
use Illuminate\Database\Seeder;

class AssessmentResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssessmentResult::factory()->times(10)->create();
    }
}
