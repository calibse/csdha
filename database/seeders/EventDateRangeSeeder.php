<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EventDate;
use Illuminate\Support\Facades\DB;

class EventDateRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::update('update event_dates set start_date = concat(date, " ", start_time)
            , end_date = concat(date, " ", end_time)');
    }
}
