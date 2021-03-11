<?php

namespace Database\Factories;

use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TermFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Term::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->randomElement(self::$terms);
        $slug = Str::of($name)->slug('-');
        return [
            'name' => $name,
            'slug' => $slug
        ];
    }

    public static $terms = [
        'First Term',
        'Second Term',
        'Third Term',
    ];
}
