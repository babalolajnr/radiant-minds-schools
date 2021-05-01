<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
}
