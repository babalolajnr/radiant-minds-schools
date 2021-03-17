<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AcademicSessionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_academic_session_index_method()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('academic-session.index'));
        $response->assertStatus(200)->assertViewIs('academicSession');
    }

    public function test_academic_session_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $startDate = now();
        $startDate = $startDate->toDateString();
        $endDate = date('Y-m-d', strtotime('+1 year', strtotime($startDate)));

        $response = $this->actingAs($user)->post(route('academic-session.store'), [
            'name' => $this->faker->word,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_academic_session_edit_method()
    {
        $user = User::factory()->create();
        $academicSession = AcademicSession::factory()->create();
        $response = $this->actingAs($user)->get(route('academic-session.edit', ['academicSession' => $academicSession]));
        $response->assertStatus(200)->assertViewIs('editAcademicSession');
    }

    public function test_academic_session_update_method()
    {
        $user = User::factory()->create();
        $academicSession = AcademicSession::factory()->create();
        $startDate = now();
        $startDate = $startDate->toDateString();
        $endDate = date('Y-m-d', strtotime('+1 year', strtotime($startDate)));

        $response = $this->actingAs($user)->patch(route('academic-session.update', ['academicSession' => $academicSession]), [
            'name' => $this->faker->word,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_user_cannot_delete_academic_session_with_relations()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['user_type' => 'master']);
        $academicSession = AcademicSession::factory()->create();
        Result::factory()->create(['academic_session_id' => $academicSession]);
        $response = $this->actingAs($user)->delete(route('academic-session.destroy', ['academicSession' => $academicSession]));
        $response->assertStatus(302)->assertSessionHas('error');
    }
}
