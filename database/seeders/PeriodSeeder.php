<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $term = Term::first();
        $academicSession = AcademicSession::first();

        if ($term == null) {
            Artisan::call('db:seed', ['--class' => 'TermSeeder']);
        }

        if ($academicSession == null) {
            Artisan::call('db:seed', ['--class' => 'AcademicSessionSeeder']);
        }

        $academicSessions = AcademicSession::all();
        $terms = Term::all();

        $rank = 0;

        $selectedDays = [];
        foreach ($academicSessions as $academicSession) {
            foreach ($terms as $term) {

                //ensure the day is unique
                do {
                    $days = mt_rand(1, 90);
                } while (in_array($days, $selectedDays));

                array_push($selectedDays, $days);

                $startDate = Carbon::createFromFormat('Y-m-d', $academicSession->start_date)
                    ->addDays($days)->toDateString();
                    
                $endDate = Carbon::createFromFormat('Y-m-d', $startDate)->addDays($days);
                $slug = Str::of("{$academicSession->name} {$term->name}")->slug('-');

                Period::create([
                    'academic_session_id' => $academicSession->id,
                    'term_id' => $term->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'slug' => $slug,
                    'rank' => $rank++
                ]);
            }
        }

        $period = Period::inRandomOrder()->first();
        $period->active = true;
        $period->save();
    }
}
