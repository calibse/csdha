<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\GoogleAccount;

class GoogleAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::all() as $user) {
            if (!$user->google_id) continue;
            $google = new GoogleAccount;
            $google->user()->associate($user);
            $google->google_id = $user->google_id;
            $google->token = $user->google_token;
            $google->refresh_token = $user->google_refresh_token;
            $google->expires_at = $user->google_expires_at;
            $google->save();
        }
    }
}
