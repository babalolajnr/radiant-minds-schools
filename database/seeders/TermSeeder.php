<?php

namespace Database\Seeders;

use App\Models\Term;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terms = [
            'First Term',
            'Second Term',
            'Third Term',
        ];
        foreach ($terms as $term) {
            Term::create(
                [
                    'name' => $term,
                ]
            );
        }
    }
}
