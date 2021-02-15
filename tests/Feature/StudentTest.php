<?php

namespace Tests\Feature;

use App\Models\Result;
use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Database\Seeders\ClassroomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
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
        $response = $this->actingAs($user)->post('/store/student', $studentInfo);
        
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
        $response = $this->actingAs($user)->post('/store/student', $studentInfo);
        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();

    }

    public function test_student_controller_index_method()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/students');
        $response->assertStatus(200);
    }

    public function test_single_student_can_be_viewed()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create()->admission_no;
        $response = $this->actingAs($user)->get('/view/student/' . $student);
        $response->assertStatus(200);
    }

    public function test_single_student_that_does_not_exist_cannot_be_viewed()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create()->admission_no;
        $response = $this->actingAs($user)->get('/view/student/igkjhr9');
        $response->assertStatus(404);
    }

    public function test_single_student_can_be_edited()
    {
        $user = User::factory()->create();
        $student = Student::factory()->create()->admission_no;
        $response = $this->actingAs($user)->get('/edit/student/' . $student);
        $response->assertStatus(200);
    }

    public function test_student_can_be_suspended()
    {
        
        $user = User::factory()->create();
        $student = Student::factory()->create(['status' => 'active'])->id;
        $response = $this->actingAs($user)->patch('/suspend/student/' . $student);
        $response->assertStatus(200);
    }

    public function test_student_can_be_activated()
    {
        
        $user = User::factory()->create();
        $student = Student::factory()->create(['status' => 'suspended'])->id;
        $response = $this->actingAs($user)->patch('/activate/student/' . $student);
        $response->assertStatus(200);
    }

    public function test_student_can_be_deactivated()
    {
        
        $user = User::factory()->create();
        $student = Student::factory()->create(['status' => 'suspended'])->id;
        $response = $this->actingAs($user)->patch('/deactivate/student/' . $student);
        $response->assertStatus(200);
    }

    public function test_student_can_be_updated()
    {
        
        $user = User::factory()->create();
        $student = Student::factory()->create()->id;
        $classroom = $this->generateTestClassroom();
        $response = $this->actingAs($user)->patch('/update/student/' . $student, $this->studentInfo($classroom));
        $response->assertStatus(302)->assertSessionHas('success')->assertSessionHasNoErrors();
    }

    public function test_student_can_be_deleted()
    {
        
        $user = User::factory()->create(['user_type' => 'master']);
        $student = Student::factory()->create()->id;
        $response = $this->actingAs($user)->delete('/delete/student/' . $student);
        $response->assertStatus(302)->assertSessionHas('success');
    }

    public function test_student_can_be_forceDeleted()
    {
        
        $user = User::factory()->create(['user_type' => 'master']);
        $student = Student::factory()->create()->id;
        $response = $this->actingAs($user)->delete('/forceDelete/student/' . $student);
        $response->assertStatus(200);
    }

    public function test_user_can_get_student_subjects()
    {
        
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $subject = Subject::factory()->create()->id;
        $student->classroom->subjects()->sync([$subject]);
        $response = $this->actingAs($user)->get('/student-subjects/' . $student->admission_no);
        $response->assertStatus(200);
    }

    public function test_user_can_get_student_sessional_results()
    {
        $user = User::factory()->create();
        $result = Result::factory()->create();
        $student = $result->student->admission_no;
        $academicSession = $result->academicSession->name;
        $response = $this->actingAs($user)->post('/results/sessional/student/' . $student, [
            'academicSession' => $academicSession
        ]);
        $response->assertStatus(200)->assertViewIs('studentSessionalResults');
    }
}
