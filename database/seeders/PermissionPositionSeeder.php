<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Permission;

class PermissionPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $position = Position::whereRaw('lower(name) = ?', ['adviser'])->first();
        $position->permissions()->sync(Permission::all());
        $position->save();
    }
}
