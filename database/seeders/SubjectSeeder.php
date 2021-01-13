<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = [
            'Art',
            'English',
            'Music',
            'History',
            'Science',
            'Geography',
            'Information technology',
            'Biology',
            'Drama',
            'Swimming',
            'Physical education'

        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'name' => $subject,
            ]);
        }
    }
}
