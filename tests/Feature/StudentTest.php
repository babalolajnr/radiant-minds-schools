<?php

namespace Tests\Feature;

use App\Models\Result;
use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\ClassroomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
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
        if (empty($classroom)) {
            $this->seed(ClassroomSeeder::class);
            $classroom = Classroom::pluck('name')->all();
            $classroom = Arr::random($classroom);
        } else {
            $classroom = Arr::random($classroom);
        }
        return $classroom;
    }

    private function studentInfo($classroom)
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'sex' => $this->faker->randomElement(['M', 'F']),
            'admission_no' => Str::random(6),
            'lg' => $this->faker->state,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'date_of_birth' => '1998-05-01',
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
        $response = $this->actingAs($user)->post(route('student.store'), $studentInfo);

        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_an_already_taken_guardian_phone_will_work()
    {
        $guardian = Guardian::factory()->create();
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
        $response = $this->actingAs($user)->post(route('student.store'), $studentInfo);
        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_student_controller_index_method()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('student.index'));
        $response->assertStatus(200);
    }

    public function test_single_student_can_be_viewed()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $response = $this->actingAs($user)->get(route('student.show', ['student' => $student]));
        $response->assertStatus(200);
    }

    public function test_single_student_that_does_not_exist_cannot_be_viewed()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('student.show', ['student' => 'asdsef']));
        $response->assertStatus(404);
    }

    public function test_single_student_can_be_edited()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $response = $this->actingAs($user)->get(route('student.edit', ['student' => $student]));
        $response->assertStatus(200);
    }

    public function test_student_can_be_activated()
    {

        $user = User::factory()->create();
        $student = Student::factory()->create(['is_active' => false]);
        $response = $this->actingAs($user)->patch(route('student.activate', ['student' => $student]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_student_can_be_deactivated()
    {

        $user = User::factory()->create();
        $student = Student::factory()->create(['is_active' => false]);
        $response = $this->actingAs($user)->patch(route('student.deactivate', ['student' => $student]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_student_can_be_updated()
    {

        $user = User::factory()->create();
        $student = Student::factory()->create();
        $classroom = $this->generateTestClassroom();
        $response = $this->actingAs($user)->patch(route('student.update', ['student' => $student]), $this->studentInfo($classroom));
        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_student_can_be_graduated()
    {

        $user = User::factory()->create();
        $student = Student::factory()->create();
        $response = $this->actingAs($user)->patch(route('student.graduate', ['student' => $student]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_student_can_be_deleted()
    {

        $user = User::factory()->create(['user_type' => 'master']);
        $student = Student::factory()->create();
        $response = $this->actingAs($user)->delete(route('student.destroy', ['student' => $student]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_student_can_be_deleted_if_guardian_has_more_than_one_child()
    {
        $user = User::factory()->create(['user_type' => 'master']);
        $guardian = Guardian::factory()->create()->id;
        $student = Student::factory()->times(2)->create(['guardian_id' => $guardian]);
        $student = $student->random();
        $response = $this->actingAs($user)->delete(route('student.destroy', ['student' => $student]));
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_user_can_get_student_sessional_results()
    {
        $user = User::factory()->create();
        $result = Result::factory()->create();
        $student = $result->student;
        $academicSession = $result->academicSession->name;
        $response = $this->actingAs($user)->get(route('student.get.sessional.results', ['student' => $student, 'academicSessionName' => $academicSession]));
        $response->assertStatus(200)->assertViewIs('studentSessionalResults');
    }

    public function test_user_can_get_alumni()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('student.get.alumni'));
        $response->assertStatus(200)->assertViewIs('alumni');
    }

    public function test_student_image_upload()
    {
        $this->withoutExceptionHandling();
        Storage::fake('public/students');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $response = $this->actingAs($user)->post(route('student.upload.image', ['student' => $student]), [
            'image' => $file
        ]);

        $response->assertStatus(302)->assertSessionHas('success');
    }
}
