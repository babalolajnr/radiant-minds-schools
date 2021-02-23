<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $subject = $this->faker->unique()->randomElement(self::$subjects);
        $slug = Str::of($subject)->slug('-');

        return [
            'name' => $subject,
            'slug' => $slug
        ];
    }

    public static $subjects = [
        'Art',
        'English',
        'Music',
        'History',
        'Science',
        'Geography',
        'Information technology',
        'Biology',
        'Drama',
        'Swimming',
        'Physical education',
        'Spanish',
        'Statistics'
    ];
}
