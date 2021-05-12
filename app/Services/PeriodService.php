<?php

namespace App\Services;

use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use Illuminate\Support\Str;

class PeriodService
{
    public function store($request)
    {
        $validatedData = $request->validated();

        $term = Term::where('name', $validatedData['term'])->first();
        $academicSession = AcademicSession::where('name', $validatedData['academic_session'])->first();

        //create slug
        $slug = Str::of("{$academicSession->name} {$term->slug}")->slug('-');

        $periodCount = Period::count();

        //check if table has records
        if ($periodCount < 1) {
            $highestRank = 1;
        } else {
            $highestRank = Period::max('rank');
            $highestRank++;
        }

        Period::create([
            'academic_session_id' => $academicSession->id,
            'term_id' => $term->id,
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'slug' => $slug,
            'rank' => $highestRank,
            'no_times_school_opened' => $validatedData['no_times_school_opened'] ?? null
        ]);
    }
}
