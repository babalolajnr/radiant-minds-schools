<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\Student;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_create_method()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $period = Period::factory()->create();
        $response = $this->actingAs($user)->get(route('attendance.create', ['student' => $student, 'periodSlug' => $period->slug]));
        $response->assertStatus(200)->assertViewIs('createAttendance');
    }

    public function test_attendance_store_or_update_method()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $period = Period::factory()->create();
        $response = $this->actingAs($user)->post(
            route(
                'attendance.store',
                ['student' => $student, 'periodSlug' => $period->slug]
            ),
            [
                'value' => mt_rand(1, 100)
            ]
        );

        $response->assertStatus(302)->assertSessionHas('success');
    }
}
