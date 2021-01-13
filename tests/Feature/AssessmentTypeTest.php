<?php

namespace Tests\Feature;

use App\Models\AssessmentType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssessmentTypeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_assessment_type_index_method()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/assessmentTypes');
        $response->assertStatus(200);
    }

    public function test_assessment_type_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/store/assessmentType', [
            'name' => 'Exam',
            'max_score' => 100
        ]);
        $response->assertStatus(200);
    }

    public function test_assessment_type_edit_method()
    {
        $user = User::factory()->create();
        $assessmentType = AssessmentType::factory()->create()->id;
        $response = $this->actingAs($user)->get('/edit/assessmentType/' . $assessmentType);
        $response->assertStatus(200);
    }

    public function test_assessment_type_update_method()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $assessmentType = AssessmentType::factory()->create()->id;
        $response = $this->actingAs($user)->patch('/update/assessmentType/' . $assessmentType, [
            'name' => 'Exam',
            'max_score' => 100
        ]);
        $response->assertStatus(200);
    }

    public function test_master_can_delete_an_assessment_type()
    {
        $user = User::factory()->create(['user_type' => 'master']);
        $assessmentType = AssessmentType::factory()->create()->id;
        $response = $this->actingAs($user)->delete('/delete/assessmentType/' . $assessmentType);
        $response->assertStatus(200);
    }

    public function test_admin_cannot_delete_assessment_type()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $assessmentType = AssessmentType::factory()->create()->id;
        $response = $this->actingAs($user)->delete('/delete/assessmentType/' . $assessmentType);
        $response->assertStatus(403);
    }
}
