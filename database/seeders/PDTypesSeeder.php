<?php

namespace Database\Seeders;

use App\Models\PDTypes;
use Database\Factories\PDTypesFactory;
use Illuminate\Database\Seeder;

class PDTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PDTypes::factory()->times(count(PDTypesFactory::$pdTypes))->create();
    }
}
