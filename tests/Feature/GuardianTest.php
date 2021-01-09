<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuardianTest extends TestCase
{
    public function test_admin_can_create_guardian()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        // $response = $this->actingAs($user)->post('/guard')
    }
}
