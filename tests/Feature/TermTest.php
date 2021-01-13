<?php

namespace Tests\Feature;

use App\Models\Term;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TermTest extends TestCase
{
    // use RefreshDatabase;
    use WithFaker;

    public function test_term_index_method()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/terms');
        $response->assertStatus(200);
    }

    public function test_term_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/store/term', [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(200);
    }

    public function test_term_edit_method()
    {
        $user = User::factory()->create();
        $term = Term::factory()->create()->id;
        $response = $this->actingAs($user)->get('/edit/term/' . $term);
        $response->assertStatus(200);
    }

    public function test_term_update_method()
    {
        $user = User::factory()->create();
        $term = Term::factory()->create()->id;
        $response = $this->actingAs($user)->patch('/update/term/' . $term, [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(200);
    }

    public function test_master_can_delete_a_term()
    {
        $user = User::factory()->create(['user_type' => 'master']);
        $term = Term::factory()->create()->id;
        $response = $this->actingAs($user)->delete('/delete/term/' . $term);
        $response->assertStatus(200);
    }

    public function test_admin_cannot_delete_term()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $term = Term::factory()->create()->id;
        $response = $this->actingAs($user)->delete('/delete/term/' . $term);
        $response->assertStatus(403);
    }
}
