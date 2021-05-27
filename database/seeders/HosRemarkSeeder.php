<?php

namespace Database\Seeders;

use App\Models\HosRemark;
use App\Models\Period;
use App\Models\Student;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class HosRemarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->progressStart(100);
        
        $data = $this->allRecords();
        $faker = Factory::create();

        $user = User::factory()->create(['user_type' => 'master']);

        foreach ($data['students'] as $student) {

            foreach ($data['periods'] as $period) {

                $record = HosRemark::where('student_id', $student->id)
                    ->where('period_id', $period->id);

                if ($record->exists()) {
                    continue;
                }

                HosRemark::create([
                    'student_id' => $student->id,
                    'period_id' => $period->id,
                    'user_id' => $user->id,
                    'remark' => $faker->realText()
                ]);
            }

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }

    private function allRecords()
    {
        $period = Period::first();
        $student = Student::first();

        //if any of the required values are empty seed their tables
        if (is_null($period)) {
            Artisan::call('db:seed', ['--class' => 'PeriodSeeder']);
        }

        if (is_null($student)) {
            Artisan::call('db:seed', ['--class' => 'StudentSeeder']);
        }

        $periods = Period::all();
        $students = Student::all();

        return [
            'periods' => $periods,
            'students' => $students,
        ];
    }
}
