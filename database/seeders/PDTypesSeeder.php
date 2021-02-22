<?php

namespace Database\Seeders;

use App\Models\PDType;
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
        PDType::factory()->times(count(PDTypesFactory::$pdTypes))->create();
    }
}
