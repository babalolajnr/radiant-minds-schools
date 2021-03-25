<?php

namespace Database\Seeders;

use App\Models\Term;
use Database\Factories\TermFactory;
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
        $terms = Term::all();
        if (count($terms) < 1) {
            Term::factory()->times(count(TermFactory::$terms))->create();
        }
    }
}
