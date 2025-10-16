<?php

namespace App\Policies;

use App\Models\GpoaActivityPartnershipType;
use App\Models\User;
use App\Models\Gpoa;
use Illuminate\Auth\Access\Response;

class GpoaActivityPartnershipTypePolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function delete(User $user, GpoaActivityPartnershipType $gpoaActivityPartnershipType): bool
    {
        $gpoaActive = Gpoa::active()->exists();
        return ($gpoaActive) ? false : true;
    }
}
