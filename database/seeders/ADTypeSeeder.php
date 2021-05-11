<?php

namespace Database\Seeders;

use App\Models\ADType;
use Database\Factories\ADTypeFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ADTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->progressStart(100);

        $adTypes = ADTypeFactory::$adTypes;

        foreach ($adTypes as $pdType) {
            $record = ADType::where('name', $pdType);

            if ($record->exists()) {
                continue;
            }

            $slug = Str::of($pdType)->slug('-');

            ADType::create([
                'name' => $pdType,
                'slug' => $slug
            ]);
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }
}
