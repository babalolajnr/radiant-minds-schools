<?php

namespace Tests\Feature;

use App\Models\AD;
use App\Models\ADType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ADTypeTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_adType_store_method()
    {
        $adType = $this->faker->word;
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('ad-type.store'), [
            'name' => $adType
        ]);

        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_adType_update_method()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $adType = ADType::factory()->create();
        $response = $this->actingAs($user)->patch(route('ad-type.update', ['adType' => $adType]), [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_adType_edit_method()
    {
        $user = User::factory()->create();
        $adType = ADType::factory()->create();
        $response = $this->actingAs($user)->get(route('ad-type.edit', ['adType' => $adType]));
        $response->assertStatus(200)->assertViewIs('editADType');
    }

    public function test_adType_index_method()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('ad-type.index'));
        $response->assertStatus(200)->assertViewIs('adTypes');
    }

    public function test_user_can_delete_a_adType()
    {
        $user = User::factory()->create();
        $adType = ADType::factory()->create();
        $response = $this->actingAs($user)->delete(route('ad-type.destroy', ['adType' => $adType]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_cannot_delete_a_adType_with_relations()
    {
        $user = User::factory()->create();
        $adType = ADType::factory()->create();
        AD::factory()->create(['a_d_type_id' => $adType->id]);
        $response = $this->actingAs($user)->delete(route('ad-type.destroy', ['adType' => $adType]));
        $response->assertStatus(302)->assertSessionHas('error');
    }
}
