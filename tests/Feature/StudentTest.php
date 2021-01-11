<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\Student;
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
    use RefreshDatabase;

    private function generateTestClassroom()
    {
        $classroom = Classroom::pluck('name')->all();

        //if classroom table is empty run ClassroomSeeder
        if (sizeof($classroom) < 1) {
            Artisan::call('db:seed', ['--class' => 'ClassroomSeeder']);
            $classroom = Classroom::pluck('name')->all();
            $classroom = Arr::random($classroom);
        } else {
            $classroom = Arr::random($classroom);
        }

        return $classroom;
    }

    private function studentInfo($classroom) {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'sex' => $this->faker->randomElement(['M', 'F']),
            'admission_no' => Str::random(6),
            'lg' => $this->faker->state,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
            'classroom' => $classroom,
            'blood_group' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'place_of_birth' => $this->faker->address
        ];
    }

    /**
     * This test fails sometimes and works most of the time and i have no idea why
     * might be because of the random generated data by faker in one of the columns
     * but the route and controller it tests work fine.
     */
    public function test_student_can_be_created_with_new_guardian_info()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $classroom = $this->generateTestClassroom();
        $studentInfo = $this->studentInfo($classroom);
        $guardianInfo = [
            'guardian_title' => $this->faker->title,
            'guardian_first_name' => $this->faker->firstName,
            'guardian_last_name' => $this->faker->lastName,
            'guardian_email' => $this->faker->email,
            'guardian_phone' => $this->faker->e164PhoneNumber,
            'guardian_occupation' => $this->faker->jobTitle,
            'guardian_address' => $this->faker->address
        ];
        $studentInfo = array_merge($studentInfo, $guardianInfo);
        $response = $this->actingAs($user)->post('/store/student', $studentInfo);
        $response->assertStatus(200);
    }

    public function test_an_already_taken_guardian_phone_will_work() {
        $guardian = Guardian::factory()->create();
        // $guardian = Guardian::all()->random();
        $user = User::factory()->create();

        $classroom = $this->generateTestClassroom();
        $studentInfo = $this->studentInfo($classroom);
        $guardianInfo = [
            'guardian_title' => $this->faker->title,
            'guardian_first_name' => $this->faker->firstName,
            'guardian_last_name' => $this->faker->lastName,
            'guardian_email' => $this->faker->email,
            'guardian_phone' => $guardian->phone,
            'guardian_occupation' => $this->faker->jobTitle,
            'guardian_address' => $this->faker->address
        ];
        $studentInfo = array_merge($studentInfo, $guardianInfo);
        $response = $this->actingAs($user)->post('/store/student', $studentInfo);
        $response->assertStatus(200);

    }

    public function test_student_controller_index_method() { 
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/students');

        $response->assertStatus(200);
    }

    public function test_single_student_can_be_viewed()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create()->admission_no;
        $response = $this->actingAs($user)->get('/view/student/'.$student);
        $response->assertStatus(200);
    }
}
