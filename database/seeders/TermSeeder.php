<?php

namespace Database\Seeders;

use App\Models\Term;
use Database\Factories\TermFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terms = TermFactory::$terms;

        foreach ($terms as $term) {

            $row = Term::where('name', $term);

            if ($row->exists()) {
                continue;
            }

            $slug = Str::of($term)->slug('-');

            Term::create(
                [
                    'name' => $term,
                    'slug' => $slug
                ]
            );
        }
    }
}
