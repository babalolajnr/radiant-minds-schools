<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
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
        $response = $this->actingAs($user)->get('/academicSessions');
        $response->assertStatus(200);
    }

    public function test_academic_session_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/store/academicSessions', [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(200);
    }

    public function test_academic_session_edit_method()
    {
        $user = User::factory()->create();
        $academicSession = AcademicSession::factory()->create()->id;
        $response = $this->actingAs($user)->get('/edit/academicSessions/' . $academicSession);
        $response->assertStatus(200);
    }

    public function test_academic_session_update_method()
    {
        $user = User::factory()->create();
        $academicSession = AcademicSession::factory()->create()->id;
        $response = $this->actingAs($user)->patch('/update/academicSessions/' . $academicSession, [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(200);
    }

    // public function test_master_can_delete_a_academic_session()
    // {
    //     $user = User::factory()->create(['user_type' => 'master']);
    //     $academicSession = AcademicSession::factory()->create()->id;
    //     $response = $this->actingAs($user)->delete('/delete/academicSessions/' . $academicSession);
    //     $response->assertStatus(200);
    // }

    // public function test_admin_cannot_delete_academic_session()
    // {
    //     $user = User::factory()->create(['user_type' => 'admin']);
    //     $academicSession = AcademicSession::factory()->create()->id;
    //     $response = $this->actingAs($user)->delete('/delete/academicSessions/' . $academicSession);
    //     $response->assertStatus(403);
    // }
}