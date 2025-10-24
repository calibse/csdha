<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Position;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Alec',
                'last_name' => 'Almiranez',
                'position' => 'President',
            ],
            [
                'first_name' => 'Daniel',
                'last_name' => 'Victorioso',
                'position' => 'Vice President for Internal Affairs',
            ],
            [
                'first_name' => 'Kristine',
                'last_name' => 'Israel',
                'position' => 'Vice President for External Affairs',
            ],
            [
                'first_name' => 'Jaira',
                'last_name' => 'Ocariza',
                'position' => 'Assistant Vice President for Research and Documentation',
            ],
            [
                'first_name' => 'Mikka',
                'last_name' => 'Esparagoza',
                'position' => 'Vice President for Finance',
            ],
            [
                'first_name' => 'Shaina',
                'last_name' => 'Cuevas',
                'position' => 'Assistant Vice President for Finance',
            ],
            [
                'first_name' => 'Kristine',
                'last_name' => 'Salazar',
                'position' => 'Vice President for Audit',
            ],
            [
                'first_name' => 'Andrhea',
                'last_name' => 'Legaspi',
                'position' => 'Assistant Vice President for Research and Documentation',
            ],
            [
                'first_name' => 'Althea',
                'last_name' => 'Aragon',
                'position' => 'Vice President for Communication',
            ],
            [
                'first_name' => 'Dale',
                'last_name' => 'Tubio',
                'position' => 'Assistant Vice President for Communication',
            ],
            [
                'first_name' => 'Grace',
                'last_name' => 'Lim',
                'position' => 'Director for Creatives',
            ],
            [
                'first_name' => 'Carmela',
                'last_name' => 'Azarcon',
                'position' => 'Co-Director for Creative',
            ],
            [
                'first_name' => 'Kyle',
                'last_name' => 'Mata',
                'position' => 'Director for Academics',
            ],
            [
                'first_name' => 'Neil',
                'last_name' => 'Linga',
                'position' => 'Co-Director for Academics',
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Arante',
                'position' => 'Director for Sports',
            ],
            [
                'first_name' => 'Kurt',
                'last_name' => 'Aldave',
                'position' => 'Co-Director for Sports',
            ],
        ];

        foreach ($users as $user) {
            $account = new User;
            $account->first_name = $user['first_name'];
            $account->last_name = $user['last_name'];
            $position = Position::whereRaw('lower(name) = ?', 
                [strtolower($user['position'])])->first();
            if (!$position) {
                $position = new Position;
                $position->name = $user['position'];
                $position->save();
            } else {
                $position->user?->position()->dissociate()->save();
            }
            $account->position()->associate($position);
            $username = '';
            $usernameExists = true;
            $username = strtolower($user['first_name']);
            $usernameExists = User::where('username', $username)->exists();
            $i = 0;
            while ($usernameExists) {
                $username = strtolower($user['first_name']) . ++$i;
                $usernameExists = User::where('username', $username)->exists();
            }
            $account->username = $username;
            $account->password = Hash::make("{$user['last_name']}@11");
            $account->save();
        }
    }
}
