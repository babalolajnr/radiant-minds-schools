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

        $academicSessions = AcademicSessionFactory::$academicSessions;
        AcademicSession::factory()->times(count($academicSessions))->create();
    }
}
