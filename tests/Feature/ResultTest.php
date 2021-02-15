<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_results() {
        //create an academic session for the test
        AcademicSession::factory()->create(['current_session' => 1]);
        
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $term = Term::factory()->create();

        $response = $this->actingAs($user)->post('/store/result/'.$student->id, [
            'ca' => mt_rand(0, 40),
            'exam' => mt_rand(0, 60),
            'term' => $term->name,
            'subject' => $subject->name
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_can_store_results_with_one_assessment() {

        AcademicSession::factory()->create(['current_session' => 1]);

        $user = User::factory()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $term = Term::factory()->create();

        $response = $this->actingAs($user)->post('/store/result/'.$student->id, [
            'ca' => mt_rand(0, 40),
            'term' => $term->name,
            'subject' => $subject->name
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }
}
