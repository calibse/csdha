<?php

namespace App\Policies;

use App\Models\StudentYear;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Gpoa;

class StudentYearPolicy
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

    public function delete(User $user, StudentYear $studentYear): bool
    {
        $gpoaActive = Gpoa::active()->exists();
        return ($gpoaActive) ? false : true;
    }
}
