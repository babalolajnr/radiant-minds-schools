<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            AcademicSessionSeeder::class,
            ADTypeSeeder::class,
            ADSeeder::class,
            ClassroomSeeder::class,
            ClassroomSubjectSeeder::class,
            GuardianSeeder::class,
            PDSeeder::class,
            PDTypeSeeder::class,
            ResultSeeder::class,
            StudentSeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            TermSeeder::class,
        ]);
    }
}
