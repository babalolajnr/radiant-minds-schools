<?php

namespace Tests\Feature;

use App\Models\ADType;
use App\Models\Period;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ADTest extends TestCase
{
    use RefreshDatabase;

    public function test_ad_controller_create_method()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $student = Student::factory()->create();
        $period = Period::factory()->create();

        $response = $this->actingAs($user)->get(route('ad.create', ['student' => $student, 'periodSlug' => $period->slug]));

        $response->assertStatus(200);
    }

    public function test_ad_controller_create_method_will_work_without_the_period_slug_parameter()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $student = Student::factory()->create();
        Period::factory()->create(['active' => true]);

        $response = $this->actingAs($user)->get(route('ad.create', ['student' => $student]));

        $response->assertStatus(200);
    }

    public function test_ad_can_be_stored()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $student = Student::factory()->create();
        $period = Period::factory()->create();

        $adTypes = ADType::factory()->times(5)->create();
        $adTypes = $adTypes->pluck('slug')->all();
        $data = [];

        foreach ($adTypes as $adType) {
            $data += [$adType => mt_rand(1, 5)];
        }

        $response = $this->actingAs($user)->post(route('ad.storeOrUpdate', ['student' => $student, 'periodSlug' => $period->slug]), [
            'adTypes' => $data
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_ad_can_be_stored_without_period_slug_parameter()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $student = Student::factory()->create();
        Period::factory()->create(['active' => true]);

        $adTypes = ADType::factory()->times(5)->create();
        $adTypes = $adTypes->pluck('slug')->all();
        $data = [];

        foreach ($adTypes as $adType) {
            $data += [$adType => mt_rand(1, 5)];
        }

        $response = $this->actingAs($user)->post(route('ad.storeOrUpdate', ['student' => $student]), [
            'adTypes' => $data
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }
}
