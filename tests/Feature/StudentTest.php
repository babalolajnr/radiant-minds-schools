<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Support\Str;

class StudentTest extends TestCase
{
    use WithFaker;
    // use RefreshDatabase;

    public function test_admin_can_create_student()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['user_type' => 'admin']);
        $classroom = Classroom::pluck('name')->all();

        //if classroom table is empty run ClassroomSeeder
        if (sizeof($classroom) < 1 ) {
            Artisan::call('db:seed', ['--class' => 'ClassroomSeeder']);
            $classroom = Classroom::pluck('name')->all();
            $classroom = Arr::random($classroom);
        } else {
            $classroom = Arr::random($classroom);
        }

        $response = $this->actingAs($user)->post('/store/student', [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'sex' => $this->faker->randomElement(['M', 'F']),
            'admission_no' => Str::random(6),
            'lg' => $this->faker->state,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
            'guardian_first_name' => $this->faker->firstName,
            'guardian_last_name' => $this->faker->lastName,
            'guardian_email' => $this->faker->email,
            'guardian_phone' => $this->faker->e164PhoneNumber,
            'classroom' => $classroom
        ]);

        $response->assertStatus(200);
    }
}
