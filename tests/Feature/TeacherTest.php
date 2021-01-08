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
    // use RefreshDatabase;

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
            'phone' => $this->faker->e164PhoneNumber,
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
        ]);
        $request->assertStatus(200);
    }

    public function test_a_single_teacher_record_can_be_viewed()
    {
        $user = User::factory()->create();
        $teacher = Teacher::factory()->create();
        $request = $this->actingAs($user)->get('/view/teacher/' . $teacher->slug);
        $request->assertStatus(200);
    }

    public function test_admin_can_access_edit_teacher_view()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $teacher = Teacher::factory()->create();
        $request = $this->actingAs($user)->get('/edit/teacher/' . $teacher->slug);
        $request->assertStatus(200);
    }

    public function test_admin_can_update_teacher()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['user_type' => 'admin']);
        $teacher = Teacher::factory()->create();
        $request = $this->actingAs($user)->patch('/update/teacher/' . $teacher->slug, [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
        ]);
        $request->assertStatus(200);

    }

    public function test_admin_can_delete_teacher()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $teacher = Teacher::factory()->create();
        $request = $this->actingAs($user)->delete('/delete/teacher/' . $teacher->id);
        $request->assertStatus(200);
    }
}
