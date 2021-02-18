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
        $class = $this->faker->unique()->randomElement(self::$classes);
        return [
            'name' => $class['name'],
            'rank' => $class['rank'],
            'teacher_id' => Teacher::factory()->create(['status' => 'active'])->id
        ];
    }

    public static $classes = [
        ['name' => 'Pre nursery', 'rank' => 0],
        ['name' => 'Nursery 1', 'rank' => 1],
        ['name' => 'Nursery 2', 'rank' => 2],
        ['name' => 'Reception', 'rank' => 3],
        ['name' => 'Grade 1', 'rank' => 4],
        ['name' => 'Grade 2', 'rank' => 5],
        ['name' => 'Grade 3', 'rank' => 6],
        ['name' => 'Grade 4', 'rank' => 7],
        ['name' => 'Grade 5', 'rank' => 8]
    ];
}
