<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
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
        $academicSessions = [
            '2010/2011',
            '2011/2012',
            '2012/2013',
        ];

        foreach ($academicSessions as $academicSession) {
            AcademicSession::create([
                'name' => $academicSession
            ]);
        }
    }
}
