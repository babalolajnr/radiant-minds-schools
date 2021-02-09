<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\Assessment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_results() {
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $academicSession = AcademicSession::factory()->create();
        $term = Term::factory()->create();

        $response = $this->actingAs($user)->post('/store/result/'.$student->id.'/'.$subject->id, [
            'ca' => mt_rand(0, 40),
            'exam' => mt_rand(0, 60),
            'academicSession' => $academicSession->name,
            'term' => $term->name,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_store_results_with_one_assessment() {
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $academicSession = AcademicSession::factory()->create();
        $term = Term::factory()->create();

        $response = $this->actingAs($user)->post('/store/result/'.$student->id.'/'.$subject->id, [
            'ca' => mt_rand(0, 40),
            'academicSession' => $academicSession->name,
            'term' => $term->name,
        ]);

        $response->assertStatus(200);
    }
}
