<?php

namespace Database\Factories;

use App\Models\Guardian;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GuardianFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Guardian::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $title = $this->faker->title;
        $fullname = $title . ' ' . $firstName . ' ' . $lastName;
        $checkIfTaken = Guardian::where('full_name', $fullname)->first();

        if (is_null($checkIfTaken)) {
            $fullname = $fullname;
        } else {
            do {
                $fullname = $fullname . Str::random(3);
                $checkIfTaken = Guardian::where('full_name', $fullname)->first();
            } while (!is_null($checkIfTaken));
        }

        return [
            'title' => $title,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
            'full_name' => $fullname,
        ];
    }
}
