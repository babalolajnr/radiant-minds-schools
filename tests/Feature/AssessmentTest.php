<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssessmentTest extends TestCase
{
    public function test_user_can_get_assessments()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/assessments');
        $response->assertStatus(200);
    }
    // public function test_user_can_create_assessment()
    // {
    // }
}
