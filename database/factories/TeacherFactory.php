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
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $fullname = $firstName . ' ' . $lastName;
        $slug = Str::of($fullname)->slug('-');
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'slug' => $slug,
            'email' => $this->faker->email,
            'phone' => '08124792224',
            'status' => $this->faker->randomElement(['active', 'inactive', 'susended']),
            'date_of_birth' => $this->faker->dateTimeThisCentury(),
        ];
    }
}
