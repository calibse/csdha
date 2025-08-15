<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $position = new Position();
        $position->name = 'President';
        $position->save();

        $position = new Position();
        $position->name = 'Vice President for External Affairs';
        $position->save();
        
        $position = new Position();
        $position->name = 'Vice President for Finance';
        $position->save();
        
        $position = new Position();
        $position->name = 'Assistant Vice President for Records';
        $position->save();
        
        $position = new Position();
        $position->name = 'Assistant Vice President for Finance';
        $position->save();
        
        $position = new Position();
        $position->name = 'Assistant Vice President for Research and Documentation';
        $position->save();
        
        $position = new Position();
        $position->name = 'Vice President for Audit';
        $position->save();
        
        $position = new Position();
        $position->name = 'Vice President for Communication';
        $position->save();
        
        $position = new Position();
        $position->name = 'Assistant Vice President for Communication';
        $position->save();
        
        $position = new Position();
        $position->name = 'Delegates Representative';
        $position->save();
        
        $position = new Position();
        $position->name = 'Director for Creatives';
        $position->save();
        
        $position = new Position();
        $position->name = 'Co-Director for Creative';
        $position->save();
        
        $position = new Position();
        $position->name = 'Director for Academics';
        $position->save();
        
        $position = new Position();
        $position->name = 'Co-Director for Academics';
        $position->save();
        
        $position = new Position();
        $position->name = 'Director for Sports';
        $position->save();
        
        $position = new Position();
        $position->name = 'Co-Director for Sports';
        $position->save();
        
        $position = new Position();
        $position->name = 'Adviser';
        $position->save();
        
    }
}
