<?php

namespace Database\Factories;

use App\Models\AcademicSession;
use Carbon\Carbon;
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

        $startDate = Carbon::now();
        $startDate->year($startYear);
        $startDate->month(mt_rand(1, 12));
        $startDate->day(mt_rand(1, 30));

        $endDate = Carbon::createFromFormat('Y-m-d', $startDate->toDateString())->addYear();

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
