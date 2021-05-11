<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use Carbon\Carbon;
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

        $this->command->getOutput()->progressStart(100);

        $academicSessions = AcademicSessionFactory::$academicSessions;

        foreach ($academicSessions as $academicSession) {
            $record = AcademicSession::where('name', $academicSession);

            if ($record->exists()) {
                continue;
            }

            /**
             * break the string and extract the first part before the '-'
             * then generate a random day and month
             */
            $eachYear = explode("-", $academicSession);
            $startYear = $eachYear[0];

            $startDate = Carbon::now();
            $startDate->year($startYear);
            $startDate->month(mt_rand(1, 12));
            $startDate->day(mt_rand(1, 30));

            $endDate = Carbon::createFromFormat('Y-m-d', $startDate->toDateString())->addYear();

            AcademicSession::create([
                'name' => $academicSession,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);
            
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }
}
