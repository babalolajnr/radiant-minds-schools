<?php

namespace Tests\Feature;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_user_can_access_create_teacher_view()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('teacher.create'));
        $response->assertStatus(200);
    }

    public function test_user_can_store_a_new_teacher()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('teacher.store'), [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
            'sex' => 'M',
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
        ]);
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_a_single_teacher_can_be_viewed()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $teacher = Teacher::factory()->create();
        $response = $this->actingAs($user)->get(route('teacher.show', ['teacher' => $teacher]));
        $response->assertStatus(200);
    }

    public function test_user_can_update_teacher()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $teacher = Teacher::factory()->create();
        $response = $this->actingAs($user)->patch(route('teacher.update', ['teacher' => $teacher]), [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
        ]);
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_can_delete_teacher()
    {
        $user = User::factory()->create();
        $teacher = Teacher::factory()->create();
        $response = $this->actingAs($user)->delete(route('teacher.destroy', ['teacher' => $teacher]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_can_activate_teacher()
    {
        $user = User::factory()->create();
        $teacher = Teacher::factory()->create(['is_active' => false]);
        $response = $this->actingAs($user)->patch(route('teacher.activate', ['teacher' => $teacher]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_can_deactivate_teacher()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $teacher = Teacher::factory()->create(['is_active' => true]);
        $response = $this->actingAs($user)->patch(route('teacher.deactivate', ['teacher' => $teacher]));
        $response->assertStatus(302)->assertSessionHas('success');
    }
}
