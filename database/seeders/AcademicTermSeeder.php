<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcademicTerm;

class AcademicTermSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 2) as $n) {
            $term = new AcademicTerm();
            $term->system = 'semester';
            $term->term_number = $n;
            $term->save();
        }
    }
}
