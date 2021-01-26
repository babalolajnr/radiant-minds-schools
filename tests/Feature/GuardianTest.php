<?php

namespace Tests\Feature;

use App\Models\Guardian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuardianTest extends TestCase
{
    public function test_guardian_controller_edit_method()
    {
        $user = User::factory()->create();
        $guardian = Guardian::factory()->create();

        $response = $this->actingAs($user)->get('/edit/guardian/'.$guardian->phone);
        $response->assertStatus(200)->assertViewIs('editGuardian');
    }
}
