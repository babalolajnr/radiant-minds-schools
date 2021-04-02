<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_user_update_method()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch(route('user.update', ['user' => $user]), [
            'first_name' => $this->faker->firstName,
            'last_name' => $user->last_name,
            'email' => $user->email
        ]);
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_password_update_method()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('user.update.password', ['user' => $user]), [
            'current_password' => '11111111', //default user factory password(8 ones)
            'new_password' => 'ANewPassword',
            'new_password_confirmation' => 'ANewPassword'
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }
}
