<?php

namespace Tests\Feature;

use App\Models\Period;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ADTest extends TestCase
{
    use RefreshDatabase;

    public function test_ad_controller_store_method()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $student = Student::factory()->create();
        $period = Period::factory()->create();

        $response = $this->actingAs($user)->get(route('ad.create', ['student' => $student, 'periodSlug' => $period->slug]));

        $response->assertStatus(200);
    }

    public function test_ad_controller_store_method_will_work_without_the_period_slug_parameter()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $student = Student::factory()->create();
        Period::factory()->create(['active' => true]);

        $response = $this->actingAs($user)->get(route('ad.create', ['student' => $student]));

        $response->assertStatus(200);
    }
}
