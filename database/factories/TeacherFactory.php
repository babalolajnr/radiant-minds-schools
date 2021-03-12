<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sex = $this->faker->randomElement(['M', 'F']);

        $firstName = $sex == 'M' ? $this->faker->firstNameMale : $this->faker->firstNameFemale;
        $lastName = $this->faker->lastName;
        $fullname = $firstName . ' ' . $lastName . ' ' . Str::random(5);
        $slug = Str::of($fullname)->slug('-');
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'sex' => $sex,
            'slug' => $slug,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
            'status' => $this->faker->randomElement(['active', 'inactive', 'suspended']),
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
        ];
    }
}
