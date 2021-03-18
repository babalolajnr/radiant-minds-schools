<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class SubjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_subject_index_method()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('subject.index'));
        $response->assertStatus(200);
    }

    public function test_subject_can_be_stored()
    {
        $user = User::factory()->create();
        $name = $this->faker->word;
        $slug = Str::of($name)->slug('-');
        $response = $this->actingAs($user)->post(route('subject.store'), [
            'name' => $name,
            'slug' => $slug
        ]);
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_subject_edit_method()
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->create();
        $response = $this->actingAs($user)->get(route('subject.edit', ['subject' => $subject]));
        $response->assertStatus(200);
    }

    public function test_subject_update_method()
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->create();
        $response = $this->actingAs($user)->patch(route('subject.update', ['subject' => $subject]), [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_can_delete_a_subject()
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->create();
        $response = $this->actingAs($user)->delete(route('subject.destroy', ['subject' => $subject]));
        $response->assertStatus(302)->assertSessionHas('success');
    }
}
