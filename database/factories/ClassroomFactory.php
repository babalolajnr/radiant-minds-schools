<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Classroom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $classes = [
            'Pre nursery',
            'Nursery 1',
            'Nursery 2',
            'Reception',
            'Grade 1',
            'Grade 2',
            'Grade 3',
            'Grade 4',
            'Grade 5',
        ];
        return [
            'name' => $this->faker->randomElement($classes),
            'teacher_id' => Teacher::factory()->create(['status' => 'active'])->id
        ];
    }
}
