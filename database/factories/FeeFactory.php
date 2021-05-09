<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Fee;
use App\Models\Period;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fee' => mt_rand(10000, 100000),
            'classroom_id' => Classroom::factory()->create()->id,
            'period_id' => Period::factory()->create()->id
        ];
    }
}
