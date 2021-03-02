<?php

namespace Tests\Feature;

use App\Models\PDType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PDTypeTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_pdType_store_method()
    {
        $pdType = $this->faker->word;
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/store/pdType', [
            'name' => $pdType
        ]);

        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_pdType_update_method()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $pdType = PDType::factory()->create();
        $response = $this->actingAs($user)->patch('/update/pdType/'. $pdType->id, [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }
}
