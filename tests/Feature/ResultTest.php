<?php

namespace Tests\Feature;

use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_results()
    {
        //create an academic session for the test
        AcademicSession::factory()->create(['current_session' => 1]);

        $user = User::factory()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $term = Term::factory()->create();

        $response = $this->actingAs($user)->post(route('result.store', ['student' => $student]), [
            'ca' => mt_rand(0, 40),
            'exam' => mt_rand(0, 60),
            'term' => $term->name,
            'subject' => $subject->name
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_can_store_results_with_one_assessment()
    {

        AcademicSession::factory()->create(['current_session' => 1]);

        $user = User::factory()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create();
        $term = Term::factory()->create();

        $response = $this->actingAs($user)->post(route('result.store', ['student' => $student]), [
            'ca' => mt_rand(0, 40),
            'term' => $term->name,
            'subject' => $subject->name
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_result_create_method()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create();

        //seed a current acaaemic session
        $academicSession = AcademicSession::factory()->create();
        $academicSession->current_session = true;
        $academicSession->save();

        $response = $this->actingAs($user)->get(route('result.create', ['student' => $student]));

        $response->assertStatus(200)->assertViewIs('createResults');
    }

    public function test_result_edit_method()
    {

        $user = User::factory()->create();
        $result = Result::factory()->create();
        $response = $this->actingAs($user)->get(route('result.edit', ['result' => $result]));

        $response->assertStatus(200)->assertViewIs('editResult');
    }

    public function test_result_destroy_method()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $result = Result::factory()->create();
        $response = $this->actingAs($user)->delete(route('result.destroy', ['result' => $result]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_show_performance_report_method()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $result = Result::factory()->create();

        //seed a subject to the classroom
        $classroom = $result->student->classroom;
        $subject = Subject::factory()->create();
        $data = [$subject->id => ['academic_session_id' => $result->academicSession->id]];
        $classroom->subjects()->attach($data);

        $response = $this->actingAs($user)->get(route('result.show.performance', ['student' => $result->student, 'termSlug' => $result->term->slug, 'academicSessionName' => $result->academicSession->name]));
        $response->assertStatus(200)->assertViewIs('performanceReport');
    }

    public function test_result_update_method()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $result = Result::factory()->create();
        session(['resultsPage' => route('result.edit', ['result' => $result])]);
        $response = $this->actingAs($user)->patch(route('result.update', ['result' => $result]), [
            'ca' => mt_rand(0, 40),
            'exam' => mt_rand(0, 60)
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }
}
