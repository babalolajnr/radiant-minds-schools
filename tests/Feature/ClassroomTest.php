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

    public function test_classroom_can_be_stored(){
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/classroom/store', [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(200);
    }
}
