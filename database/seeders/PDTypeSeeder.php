<?php

namespace Database\Seeders;

use App\Models\PDType;
use Database\Factories\PDTypeFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class PDTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->progressStart(100);

        $pdTypes = PDTypeFactory::$pdTypes;

        foreach ($pdTypes as $pdType) {
            $record = PDType::where('name', $pdType);

            if ($record->exists()) {
                continue;
            }

            $slug = Str::of($pdType)->slug('-');

            PDType::create([
                'name' => $pdType,
                'slug' => $slug
            ]);
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }
}
