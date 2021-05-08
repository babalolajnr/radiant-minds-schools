<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_fee_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $user = User::factory(['user_type' => 'master'])->create();
        $classroom = Classroom::factory()->create();
        $period = Period::factory()->create();

        $response = $this->actingAs($user)->post(route('fee.store'), [
            'classroom' => $classroom->name,
            'period' => $period->slug,
            'fee' => '20000'
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }
}
