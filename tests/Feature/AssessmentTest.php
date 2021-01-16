<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssessmentTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_get_assessments()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/assessments');
        $response->assertStatus(200);
    }

    public function test_user_can_create_assessment()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/store/assessment', [
            'term' => Term::factory()->create()->name,
            'assessment_type' => AssessmentType::factory()->create()->name,
            'academic_session' => AcademicSession::factory()->create()->name,
        ]);

        $response->assertStatus(200);
    }

    public function test_record_that_exists_cannot_be_stored()
    {
        $user = User::factory()->create();
        $assessment = Assessment::factory()->create();
        $response = $this->actingAs($user)->post('/store/assessment', [
            'term' => Term::find($assessment->term_id)->name,
            'assessment_type' => AssessmentType::find($assessment->assessment_type_id)->name,
            'academic_session' => AcademicSession::find($assessment->academic_session_id)->name,
        ]);

        $response->assertStatus(302);
    }
}
