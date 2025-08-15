<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentYear;
use App\Models\StudentSection;
use App\Services\Format;

class StudentYearAndSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 4) as $n) {
            $year = new StudentYear();
            $year->year = $n;
            $year->label = Format::ordinal($n) . ' Year';
            $year->save();
        }
        $year = new StudentYear();
        $year->year = 'L'; 
        $year->label = 'Ladderize';
        $year->save();

        $section = new StudentSection();
        $section->section = 1;
        $section->save();

        $section = new StudentSection();
        $section->section = 'L';
        $section->save();
    }
}
