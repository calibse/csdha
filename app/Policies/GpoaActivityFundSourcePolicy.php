<?php

namespace App\Policies;

use App\Models\GpoaActivityFundSource;
use App\Models\User;
use App\Models\Gpoa;
use Illuminate\Auth\Access\Response;

class GpoaActivityFundSourcePolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function delete(User $user, GpoaActivityFundSource $gpoaActivityFundSource): bool
    {
        $gpoaActive = Gpoa::active()->exists();
        $hasPerm = $user->hasPerm('settings.edit');
        return ($gpoaActive || !$hasPerm) ? false : true;
    }
}
