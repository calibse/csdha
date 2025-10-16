<?php

namespace App\Policies;

use App\Models\StudentSection;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Gpoa;

class StudentSectionPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        $gpoaActive = Gpoa::active()->exists();
        return ($gpoaActive) ? false : true;
    }

    public function delete(User $user, StudentSection $studentSection): bool
    {
        $gpoaActive = Gpoa::active()->exists();
        return ($gpoaActive) ? false : true;
    }
}
