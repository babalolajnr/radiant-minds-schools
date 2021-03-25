<?php

namespace Database\Seeders;

use App\Models\ADType;
use Database\Factories\ADTypeFactory;
use Illuminate\Database\Seeder;

class ADTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adTypes = ADType::all();
        if (count($adTypes) < 1) {
            ADType::factory()->times(count(ADTypeFactory::$adTypes))->create();
        }
    }
}
