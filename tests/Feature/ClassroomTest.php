<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    // use RefreshDatabase;
    use WithFaker;

    public function test_classroom_index_method(){
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/classrooms');
        $response->assertStatus(200);
    }
}
