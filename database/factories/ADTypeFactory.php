<?php

namespace Database\Factories;

use App\Models\ADType;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ADTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ADType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $adType = $this->faker->unique()->randomElement(self::$adTypes);
        $slug = Str::of($adType)->slug('-');
        
        return [
            'name' => $adType,
            'slug' => $slug
        ];
    }

    public static $adTypes = [
        'Punctuality',
        'Attendance',
        'Neatness',
        'Politeness',
        'Attentiveness',
        'Self control/calmness',
        'Obedience',
        'Relationship with others',
    ];
}
