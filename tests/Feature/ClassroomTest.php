<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_classroom_index_method()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/classrooms');
        $response->assertStatus(200)->assertViewIs('classrooms');
    }

    public function test_classroom_can_be_stored()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/store/classroom', [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_classroom_edit_method()
    {
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create()->id;
        $response = $this->actingAs($user)->get('/edit/classroom/' . $classroom);
        $response->assertStatus(200)->assertViewIs('editClassroom');
    }

    public function test_classroom_update_method()
    {
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create()->id;
        $response = $this->actingAs($user)->patch('/update/classroom/' . $classroom, [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_master_can_delete_a_classroom()
    {
        $user = User::factory()->create(['user_type' => 'master']);
        $classroom = Classroom::factory()->create()->id;
        $response = $this->actingAs($user)->delete('/delete/classroom/' . $classroom);
        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_admin_cannot_delete_classroom()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $classroom = Classroom::factory()->create()->id;
        $response = $this->actingAs($user)->delete('/delete/classroom/' . $classroom);
        $response->assertStatus(403);
    }

    public function test_classroom_subjects_can_be_updated()
    {
        $user = User::factory()->create();
        $subjects = $this->generateTestSubjects();
        $classroom = Classroom::factory()->create()->id;
        $response = $this->actingAs($user)->post('/update/classroom-subjects/' . $classroom, [
            'subjects' => $subjects
        ]);

        $response->assertStatus(200);
        
    }

    private function generateTestSubjects()
    {
        $subjects = Subject::pluck('name')->all();

        //if subject table is empty run subjectSeeder
        if (sizeof($subjects) < 1) {
            Artisan::call('db:seed', ['--class' => 'SubjectSeeder']);
            $subjects = Subject::pluck('name')->all();
        }

        $selectedSubjects = Arr::random($subjects, 5);
        return $selectedSubjects;
    }
}
