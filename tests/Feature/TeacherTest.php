<?php

namespace Tests\Feature;

use App\Models\User;
use App\Utilities\TestUtilities;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_admin_can_access_create_teacher_view()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $request = $this->actingAs($user)->get('/create/teacher');
        $request->assertStatus(200);
    }

    public function test_admin_can_store_a_new_teacher()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $request = $this->actingAs($user)->post('/store/teacher', [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => '08124792224',
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
        ]);
        $request->assertStatus(200);
    }
}
