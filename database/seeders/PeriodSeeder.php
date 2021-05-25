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

        $period = Period::first();

        if ($period == null) {
            $rank = 0;
        } else {
            $rank = Period::get()->max('rank');
        }

        $selectedDays = [];
        foreach ($academicSessions as $academicSession) {
            foreach ($terms as $term) {

                //check if academic session and term exist on the same row
                $row = Period::where('academic_session_id', $academicSession->id)->where('term_id', $term->id);

                if ($row->exists()) {
                    continue;
                }

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
                    'rank' => ++$rank,
                    'no_times_school_opened' => mt_rand(100, 120)
                ]);
            }
        }

        $activePeriod = Period::activePeriod();
        if ($activePeriod == null) {
            $period = Period::inRandomOrder()->first();
            $period->active = true;
            $period->save();
        }
    }
}
