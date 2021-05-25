<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\Remark;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RemarkTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_get_remark_screen()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $student = Student::factory()->create();
        $period = Period::factory()->create();

        $response = $this->actingAs($user)->get(route('remark.create', ['student' => $student, 'periodSlug' => $period->slug]));

        $response->assertStatus(200)->assertViewIs('createRemark');
    }

    public function test_class_teacher_can_get_remark_screen()
    {
        $this->withoutExceptionHandling();

        $student = Student::factory()->create();
        $teacher = $student->classroom->teacher;
        $period = Period::factory()->create();

        $response = $this->actingAs($teacher, 'teacher')->get(route('remark.create', ['student' => $student, 'periodSlug' => $period->slug]));

        $response->assertStatus(200)->assertViewIs('createRemark');
    }

    public function test_non_class_teacher_cannot_get_remark_screen()
    {

        $student = Student::factory()->create();
        $teacher = Teacher::factory()->create();
        $period = Period::factory()->create();

        $response = $this->actingAs($teacher, 'teacher')->get(route('remark.create', ['student' => $student, 'periodSlug' => $period->slug]));

        $response->assertStatus(403);
    }

    public function test_remark_can_be_stored()
    {
        $student = Student::factory()->create();
        $teacher = $student->classroom->teacher;
        $period = Period::factory()->create();

        $response = $this->actingAs($teacher, 'teacher')->post(
            route('remark.storeOrUpdate', ['student' => $student, 'periodSlug' => $period->slug]),
            [
                'class_teacher_remark' => $this->faker->realText,
                'hos_remark' => $this->faker->realText
            ]
        );

        $response->assertStatus(302);
    }

}
