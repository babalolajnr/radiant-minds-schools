<?php

namespace Database\Seeders;

use App\Models\PDType;
use Database\Factories\PDTypeFactory;
use Illuminate\Database\Seeder;

class PDTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PDType::factory()->times(count(PDTypeFactory::$pdTypes))->create();
    }
}
