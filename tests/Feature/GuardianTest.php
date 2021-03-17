<?php

namespace Tests\Feature;

use App\Models\Guardian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuardianTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_guardian_controller_edit_method()
    {
        $user = User::factory()->create();
        $guardian = Guardian::factory()->create();
        $response = $this->actingAs($user)->get(route('guardian.edit', ['guardian' => $guardian]));
        $response->assertStatus(200)->assertViewIs('editGuardian');
    }

    public function test_guardian_controller_update_method()
    {
        $user = User::factory()->create();
        $guardian = Guardian::factory()->create();

        $response = $this->actingAs($user)->patch(route('guardian.update', ['guardian' => $guardian]), [
            'title' => $this->faker->title,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
            'occupation' => $this->faker->jobTitle,
            'address' => $this->faker->address
        ]);

        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }
}
