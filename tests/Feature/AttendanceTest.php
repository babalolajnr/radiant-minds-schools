<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
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
        $term = Term::factory()->create();
        $academicSession = AcademicSession::factory()->create();
        $response = $this->actingAs($user)->get(route('attendance.create', ['student' => $student, 'termSlug' => $term->slug, 'academicSessionName' => $academicSession->name]));
        $response->assertStatus(200)->assertViewIs('createAttendance');
    }

    // public function test_attendance_store_or_update_method()
    // {
    //     $user = User::factory()->create();
    //     $student = Student::factory()->create();
    //     $term = Term::factory()->create();
    //     $academicSession = AcademicSession::factory()->create();
    //     $response = $this->actingAs($user)->post(route('attendance.store', ['student' => $student, 'termSlug' => $term->slug, 'academicSessionName' => $academicSession->name]));
    // }
}
