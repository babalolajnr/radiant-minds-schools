<?php

namespace Tests\Feature;

use App\Models\HosRemark;
use App\Models\Period;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HosRemarkTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_get_hos_remark_screen()
    {
        $this->withoutExceptionHandling();

        $student = Student::factory()->create();
        $user = User::factory()->create();
        Period::factory()->create(['active' => true]);

        $response = $this->actingAs($user)->get(route('remark.hos.create', ['student' => $student]));

        $response->assertStatus(200)->assertViewIs('createHosRemark');
    }

    public function test_hos_remark_can_be_stored()
    {
        $this->withoutExceptionHandling();

        $student = Student::factory()->create();
        $user = User::factory()->create();

        Period::factory()->create(['active' => true]);

        $response = $this->actingAs($user)->post(
            route('remark.hos.storeOrUpdate', ['student' => $student]),
            [
                'remark' => $this->faker->realText,
            ]
        );

        $response->assertStatus(302);
    }

    public function test_hos_remark_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $student = Student::factory()->create();
        $user = User::factory()->create();

        HosRemark::factory()->create(['student_id' => $student->id]);

        $response = $this->actingAs($user)->post(
            route('remark.hos.storeOrUpdate', ['student' => $student]),
            [
                'remark' => $this->faker->realText,
            ]
        );

        $response->assertStatus(302);
    }
}
