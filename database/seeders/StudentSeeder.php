<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = new Student();
        $student->student_id = '2020-00395-TG-0';
        $student->first_name = 'Calib';
        $student->last_name = 'Serrano';
        $student->course()->associate(
            Course::firstWhere('acronym', '=', 'BSIT'));
        $student->year = 4;
        $student->section = 1;
        $student->email = 'calib@email.com';
        $student->save();
    }
}
