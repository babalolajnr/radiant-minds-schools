<?php

namespace Database\Seeders;

use App\Models\Subject;
use Database\Factories\SubjectFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = SubjectFactory::$subjects;

        foreach ($subjects as $subject) {

            $row = Subject::where('name', $subject);

            if ($row->exists()) {
                continue;
            }

            $slug = Str::of($subject)->slug('-');
            Subject::create(
                [
                    'name' => $subject,
                    'slug' => $slug
                ]
            );
        }
    }
}
