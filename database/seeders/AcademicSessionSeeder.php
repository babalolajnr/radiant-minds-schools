<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use Database\Factories\AcademicSessionFactory;
use Illuminate\Database\Seeder;

class AcademicSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $academicSessions = AcademicSession::all();
        if (count($academicSessions) < 1) {
            
            $academicSessions = AcademicSessionFactory::$academicSessions;

            AcademicSession::factory()->times(count($academicSessions))->create();
        }
    }
}
