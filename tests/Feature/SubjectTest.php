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
        $response = $this->actingAs($user)->get('/terms');
        $response->assertStatus(200);
    }

    public function test_subject_can_be_stored()
    {
        $user = User::factory()->create();
        $name = $this->faker->word;
        $slug = Str::of($name)->slug('-');
        $response = $this->actingAs($user)->post('/store/subject', [
            'name' => $name,
            'slug' => $slug
        ]);
        $response->assertStatus(200);
    }

    public function test_subject_edit_method()
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->create()->id;
        $response = $this->actingAs($user)->get('/edit/subject/' . $subject);
        $response->assertStatus(200);
    }

    public function test_subject_update_method()
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->create()->id;
        $response = $this->actingAs($user)->patch('/update/subject/' . $subject, [
            'name' => $this->faker->word
        ]);
        $response->assertStatus(200);
    }

    public function test_master_can_delete_a_subject()
    {
        $user = User::factory()->create(['user_type' => 'master']);
        $subject = Subject::factory()->create()->id;
        $response = $this->actingAs($user)->delete('/delete/subject/' . $subject);
        $response->assertStatus(200);
    }

    public function test_admin_cannot_delete_subject()
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $subject = Subject::factory()->create()->id;
        $response = $this->actingAs($user)->delete('/delete/subject/' . $subject);
        $response->assertStatus(403);
    }
}
