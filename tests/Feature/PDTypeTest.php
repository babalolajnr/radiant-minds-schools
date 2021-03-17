<?php

namespace Tests\Feature;

use App\Models\PD;
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

        $response = $this->actingAs($user)->post(route('pd-type.store'), [
            'name' => $pdType
        ]);

        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_pdType_update_method()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $pdType = PDType::factory()->create();
        $response = $this->actingAs($user)->patch(route('pd-type.update', ['pdType' => $pdType]), [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_pdType_edit_method()
    {
        $user = User::factory()->create();
        $pdType = PDType::factory()->create();
        $response = $this->actingAs($user)->get(route('pd-type.edit', ['pdType' => $pdType]));
        $response->assertStatus(200)->assertViewIs('editPDType');
    }

    public function test_pdType_index_method()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('pd-type.index'));
        $response->assertStatus(200)->assertViewIs('pdTypes');
    }

    public function test_user_can_delete_a_pdType()
    {
        $user = User::factory()->create();
        $pdType = PDType::factory()->create();
        $response = $this->actingAs($user)->delete(route('pd-type.destroy', ['pdType' => $pdType]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_cannot_delete_a_pdType_with_relations()
    {
        $user = User::factory()->create();
        $pdType = PDType::factory()->create();
        PD::factory()->create(['p_d_type_id' => $pdType->id]);
        $response = $this->actingAs($user)->delete(route('pd-type.destroy', ['pdType' => $pdType]));
        $response->assertStatus(302)->assertSessionHas('error');
    }
}
