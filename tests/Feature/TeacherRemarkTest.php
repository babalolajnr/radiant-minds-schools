<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\TeacherRemark;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherRemarkTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_teacher_can_get_remark_screen()
    {
        $this->withoutExceptionHandling();

        $student = Student::factory()->create();
        $teacher = $student->classroom->teacher;
        Period::factory()->create(['active' => true]);

        $response = $this->actingAs($teacher, 'teacher')->get(route('remark.teacher.create', ['student' => $student]));

        $response->assertStatus(200)->assertViewIs('createTeacherRemark');
    }


    public function test_non_class_teacher_cannot_get_remark_screen()
    {

        $student = Student::factory()->create();
        $teacher = Teacher::factory()->create(['is_active' => true]);
        
        Period::factory()->create(['active' => true]);

        $response = $this->actingAs($teacher, 'teacher')->get(route('remark.teacher.create', ['student' => $student]));

        $response->assertStatus(403);
    }

    public function test_remark_can_be_stored()
    {
        $this->withoutExceptionHandling();

        $student = Student::factory()->create();
        $teacher = $student->classroom->teacher;

        Period::factory()->create(['active' => true]);

        $response = $this->actingAs($teacher, 'teacher')->post(
            route('remark.teacher.storeOrUpdate', ['student' => $student]),
            [
                'remark' => $this->faker->realText,
            ]
        );

        $response->assertStatus(302);
    }

    public function test_remark_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $student = Student::factory()->create();
        $teacher = $student->classroom->teacher;

        TeacherRemark::factory()->create(['student_id' => $student->id]);

        $response = $this->actingAs($teacher, 'teacher')->post(
            route('remark.teacher.storeOrUpdate', ['student' => $student]),
            [
                'remark' => $this->faker->realText,
            ]
        );

        $response->assertStatus(302);
    }
}
