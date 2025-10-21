<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            PositionSeeder::class,
            UserSeeder::class,
            PermissionPositionSeeder::class,
            CourseSeeder::class,
            StudentYearAndSectionSeeder::class,
            StudentSeeder::class,
            RoleSeeder::class,
            AcademicTermSeeder::class
	]);
    }
}
