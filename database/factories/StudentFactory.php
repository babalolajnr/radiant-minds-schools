<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $classroom = Classroom::pluck('id')->all();

        if (!empty($classroom)) {
            $classroom = Arr::random($classroom);
        } else {
            Artisan::call('db:seed', ['--class' => 'ClassroomSeeder']);
            $classroom = Classroom::inRandomOrder()->first();
        }

        $guardian = Guardian::factory()->create();
        $sex = $this->faker->randomElement(['M', 'F']);
        $firstName = $sex == 'M' ? $this->faker->firstNameMale : $this->faker->firstNameFemale;
        $graduated = $this->faker->randomElement([true, false]);

        if ($graduated) {
            $graduated_at = $this->faker->dateTimeBetween('-3 years');
        } else {
            $graduated_at = null;
        }
        return [
            'first_name' => $firstName,
            'last_name' => $guardian->last_name,
            'sex' => $sex,
            'admission_no' => Str::random(6),
            'lg' => $this->faker->state,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'date_of_birth' => $this->faker->dateTimeThisDecade(),
            'classroom_id' => $classroom,
            'blood_group' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'place_of_birth' => $this->faker->address,
            'guardian_id' => $guardian->id,
            'is_active' => $this->faker->randomElement([true, false]),
            'graduated_at' => $graduated_at
        ];
    }
}
