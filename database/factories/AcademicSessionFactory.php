<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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


        $name = $this->faker->unique()->randomElement(self::$academicSessions);

        /**
         * break the string and extract the first part before the '/'
         * then generate a random day and month
         */
        $eachYear = explode("/", $name);
        $startYear = $eachYear[0];
        $startDay = mt_rand(1, 30);
        $startMonth = mt_rand(1, 12);

        $startDate = $startDay . '-' . $startMonth . '-' . $startYear;
        $startDate = date('Y-m-d', strtotime($startDate));

        $endDate = date('Y-m-d', strtotime('+1 year', strtotime($startDate)));

        return [
            'name' => $name,
            'slug' => Str::of($name)->slug('-'),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    public static $academicSessions = [
        '2010/2011',
        '2011/2012',
        '2012/2013',
        '2013/2014'
    ];
}
