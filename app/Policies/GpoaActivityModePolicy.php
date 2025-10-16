<?php

namespace App\Policies;

use App\Models\GpoaActivityMode;
use App\Models\User;
use App\Models\Gpoa;
use Illuminate\Auth\Access\Response;

class GpoaActivityModePolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function delete(User $user, GpoaActivityMode $gpoaActivityMode): bool
    {
        $gpoaActive = Gpoa::active()->exists();
        return ($gpoaActive) ? false : true;
    }
}
