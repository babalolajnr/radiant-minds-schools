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

    public function test_admin_can_access_create_teacher_view()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $response = $this->actingAs($user)->get('/create/teacher');
        $response->assertStatus(200);
    }

    public function test_admin_can_store_a_new_teacher()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $response = $this->actingAs($user)->post('/store/teacher', [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
        ]);
        $response->assertStatus(200);
    }

    public function test_a_single_teacher_record_can_be_viewed()
    {
        $user = User::factory()->create();
        $teacher = Teacher::factory()->create();
        $response = $this->actingAs($user)->get('/view/teacher/' . $teacher->slug);
        $response->assertStatus(200);
    }

    public function test_admin_can_access_edit_teacher_view()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $teacher = Teacher::factory()->create();
        $response = $this->actingAs($user)->get('/edit/teacher/' . $teacher->slug);
        $response->assertStatus(200);
    }

    public function test_admin_can_update_teacher()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['user_type' => 'admin']);
        $teacher = Teacher::factory()->create();
        $response = $this->actingAs($user)->patch('/update/teacher/' . $teacher->slug, [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
        ]);
        $response->assertStatus(200);

    }

    public function test_admin_can_delete_teacher()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $teacher = Teacher::factory()->create();
        $response = $this->actingAs($user)->delete('/delete/teacher/' . $teacher->id);
        $response->assertStatus(200);
    }

    public function test_master_can_force_delete_teacher()
    {
        $user = User::factory()->create(['user_type' => 'master']);
        $teacher = Teacher::factory()->create();
        $response = $this->actingAs($user)->delete('/forceDelete/teacher/' . $teacher->id);
        $response->assertStatus(200);
    }

    public function test_master_can_suspend_teacher()
    {
        $user = User::factory()->create(['user_type' => 'master']);
        $teacher = Teacher::factory()->create(['status' => 'active']);
        $response = $this->actingAs($user)->patch('/suspend/teacher/' . $teacher->id);
        $response->assertStatus(200);
    }

    public function test_master_can_activate_teacher()
    {
        $user = User::factory()->create(['user_type' => 'master']);
        $teacher = Teacher::factory()->create(['status' => 'suspended']);
        $response = $this->actingAs($user)->patch('/activate/teacher/' . $teacher->id);
        $response->assertStatus(200);
    }

    public function test_master_can_deactivate_teacher()
    {
        $user = User::factory()->create(['user_type' => 'master']);
        $teacher = Teacher::factory()->create(['status' => 'active']);
        $response = $this->actingAs($user)->patch('/deactivate/teacher/' . $teacher->id);
        $response->assertStatus(200);
    }
}
