<?php

namespace Database\Factories;

use App\Models\PDType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PDTypesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PDType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $pdType = $this->faker->unique()->randomElement(self::$pdTypes);
        $slug = Str::of($pdType)->slug('-');
        return [
            'name' => $pdType,
            'slug' => $slug
        ];
    }

    public static $pdTypes = [
        'Handwriting',
        'Fluency',
        'Sports',
        'Crafts',
        'Drawing',
        'Public Speaking'
    ];
}
