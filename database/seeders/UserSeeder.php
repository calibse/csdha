<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Position;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		$user = new User();
		$user->first_name = 'Crisostomo';
		$user->last_name = 'Ibarra';
		$user->username = 'cris';
        $user->password = Hash::make('123');
		$position = Position::whereRaw('lower(name) = ?', ['adviser'])->first();
		$user->position()->associate($position);
		$user->save();

		$user = new User();
		$user->first_name = 'Maria';
		$user->last_name = 'Clara';
		$user->username = 'maria';
        $user->password = Hash::make('123');
		$user->save();
		
    }
}
