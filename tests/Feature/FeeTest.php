<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Fee;
use App\Models\Period;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_fee_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $user = User::factory(['user_type' => 'master'])->create();
        $classroom = Classroom::factory()->create();
        $period = Period::factory()->create();

        $response = $this->actingAs($user)->post(route('fee.store'), [
            'classroom' => $classroom->name,
            'period' => $period->slug,
            'amount' => '20000'
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_fee_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $user = User::factory(['user_type' => 'master'])->create();
        $fee = Fee::factory()->create();

        $response = $this->actingAs($user)->delete(route('fee.destroy', ['fee' => $fee]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_can_get_fee_edit_page()
    {
        $this->withoutExceptionHandling();
        $user = User::factory(['user_type' => 'master'])->create();
        $fee = Fee::factory()->create();

        $response = $this->actingAs($user)->get(route('fee.edit', ['fee' => $fee]));
        $response->assertStatus(200)->assertViewIs('editFee');
    }

    public function test_fee_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $user = User::factory(['user_type' => 'master'])->create();
        $fee = Fee::factory()->create();

        $amount = mt_rand(10000, 100000);
        
        $response = $this->actingAs($user)->patch(route('fee.update', ['fee' => $fee]), [
            'amount' => "{$amount}"
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_fee_that_is_not_numeric_will_not_be_stored()
    {
        $user = User::factory(['user_type' => 'master'])->create();
        $classroom = Classroom::factory()->create();
        $period = Period::factory()->create();

        $response = $this->actingAs($user)->post(route('fee.store'), [
            'classroom' => $classroom->name,
            'period' => $period->slug,
            'amount' => '2fdsfs0'
        ]);

        $response->assertStatus(302)->assertSessionHasErrors('amount');
    }
}
