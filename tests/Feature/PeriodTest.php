<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\Period;
use App\Models\Term;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class PeriodTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_period_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['user_type' => 'master']);

        $response = $this->actingAs($user)->post(
            route('period.store'),
            [
                'academic_session' => AcademicSession::factory()->create()->name,
                'term' => Term::factory()->create()->name,
                'start_date' => now(),
                'end_date' => now()->addMonths(4)
            ]
        );

        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_period_can_be_stored_when_there_are_other_period_records()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['user_type' => 'master']);

        Period::factory()->create();

        $response = $this->actingAs($user)->post(
            route('period.store'),
            [
                'academic_session' => AcademicSession::factory()->create()->name,
                'term' => Term::factory()->create()->name,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(4)->toDateString()
            ]
        );

        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_period_with_date_range_that_overlaps_another_period_cannot_be_stored()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['user_type' => 'master']);

        $periodStartDate = Carbon::now();
        $periodStartDate->year(2021)->month(4)->day(10);

        $periodEndDate = Carbon::now();
        $periodEndDate->year(2021)->month(10)->day(10);

        Period::factory()->create(['start_date' => $periodStartDate, 'end_date' => $periodEndDate]);

        $response = $this->actingAs($user)->post(
            route('period.store'),
            [
                'academic_session' => AcademicSession::factory()->create()->name,
                'term' => Term::factory()->create()->name,
                'start_date' => '2021-6-20',
                'end_date' => '2021-7-20'
            ]
        );

        $response->assertStatus(302)->assertSessionHas('error');
    }

    public function test_period_with_date_range_that_does_not_overlap_another_period_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['user_type' => 'master']);

        $periodStartDate = Carbon::now();
        $periodStartDate->year(2022)->month(4)->day(10);

        $periodEndDate = Carbon::now();
        $periodEndDate->year(2023)->month(10)->day(10);

        $this->seed('PeriodSeeder');

        $academicSessionName = '2022/2023';
        $slug = str_replace('/', '-', $academicSessionName);

        $academicSession = AcademicSession::create([
            'name' => '2022/2023',
            'slug' => $slug,
            'start_date' => $periodStartDate,
            'end_date' => $periodEndDate,
            'rank' => mt_rand(20, 40)
        ])->name;

        $termName = 'Fifth term';
        $term =  Term::create(['name' => $termName, 'slug' => Str::of($termName)->slug('-')])->name;

        $response = $this->actingAs($user)->post(
            route('period.store'),
            [
                'academic_session' => $academicSession,
                'term' => $term,
                'start_date' => '2022-10-10',
                'end_date' => '2023-12-10'
            ]
        );

        $response->assertStatus(302)->assertSessionHas('success');
    }
}
