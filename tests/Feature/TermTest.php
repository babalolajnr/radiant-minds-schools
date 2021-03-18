<?php

namespace Tests\Feature;

use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TermTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_term_index_method()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('term.index'));
        $response->assertStatus(200);
    }

    public function test_term_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('term.store'), [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_term_edit_method()
    {
        $user = User::factory()->create();
        $term = Term::factory()->create();
        $response = $this->actingAs($user)->get(route('term.edit', ['term' => $term]));
        $response->assertStatus(200);
    }

    public function test_term_update_method()
    {
        $user = User::factory()->create();
        $term = Term::factory()->create();
        $response = $this->actingAs($user)->patch(route('term.update', ['term' => $term]), [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_can_delete_a_term()
    {
        $user = User::factory()->create();
        $term = Term::factory()->create();
        $response = $this->actingAs($user)->delete(route('term.destroy', ['term' => $term]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

}
