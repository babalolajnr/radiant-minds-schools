<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PDTypeTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    
    public function test_store_method()
    {
        $pdType = $this->faker->word;
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/store/pdType', [
            'name' => $pdType
        ]);

        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }
}
