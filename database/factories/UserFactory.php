<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'user_type' => $this->faker->randomElement(['admin', 'master']),
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'is_verified' => true,
            'is_active' => true,
            'password' => bcrypt(11111111), // password - 8 ones
            'remember_token' => Str::random(10),
        ];
    }
}
