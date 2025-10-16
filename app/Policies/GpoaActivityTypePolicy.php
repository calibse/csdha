<?php

namespace App\Policies;

use App\Models\GpoaActivityType;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Gpoa;

class GpoaActivityTypePolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function delete(User $user, GpoaActivityType $gpoaActivityType): bool
    {
        $gpoaActive = Gpoa::active()->exists();
        return ($gpoaActive) ? false : true;
    }
}
