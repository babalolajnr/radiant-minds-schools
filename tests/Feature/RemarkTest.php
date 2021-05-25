<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\Student;
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
}
