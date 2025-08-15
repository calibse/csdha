<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course = new Course;
        $course->name = 'Bachelor of Science in Information Technology';
        $course->acronym = 'BSIT';
        $course->save();

        $course = new Course;
        $course->name = 'Diploma in Information Technology';
        $course->acronym = 'DIT';
        $course->save();
        
    }
}
