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
        $request = $this->actingAs($user)->get('/create/teacher');
        $request->assertStatus(200);
    }

    public function test_admin_can_store_a_new_teacher()
    {
        // $this->withoutExceptionHandling();
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

    public function test_a_single_teacher_record_can_be_viewed()
    {
        $user = User::factory()->create();
        $teacher = Teacher::factory()->create();
        $request = $this->actingAs($user)->get('/view/teacher/' . $teacher->slug);
        $request->assertStatus(200);
    }
}
