<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResultTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_store_assessment()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $assessment =  Assessment::factory()->create();

        $response = $this->actingAs($user)->post('store/assessment-result/' . $student->id . '/' . $subject->id, [
            'mark' => mt_rand(0, $assessment->assessmentType->max_score), 
            'assessment' => $assessment->name
        ]);

        $response->assertStatus(200);
    }
}
