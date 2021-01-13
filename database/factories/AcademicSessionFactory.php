<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicSessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AcademicSession::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $academicSession = [
            '2010/2011',
            '2011/2012',
            '2012/2013',
            '2013/2014',
            '2014/2015',
        ];
        return [
            'name' => $this->faker->unique()->randomElement($academicSession)
        ];
    }
}
