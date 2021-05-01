<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\AcademicSessionTerm;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class AcademicSessionTermSeeder extends Seeder
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

        foreach ($academicSessions as $academicSession) {
            foreach ($terms as $term) {
                $startDate = Carbon::createFromFormat('Y-m-d', $academicSession->start_date)
                    ->addDays(mt_rand(1, 20))->toDateString();
                $endDate = Carbon::createFromFormat('Y-m-d', $startDate)->addDays(mt_rand(30, 90));
                $slug = Str::of("{$academicSession->name} {$term->name}")->slug('-');

                AcademicSessionTerm::create([
                    'academic_session_id' => $academicSession->id,
                    'term_id' => $term->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'slug' => $slug
                ]);
            }
        }

        $period = AcademicSessionTerm::inRandomOrder()->first();
        $period->active = true;
        $period->save();
    }
}
