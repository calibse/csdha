<?php

namespace App\Policies;

use App\Models\Gpoa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GpoaPolicy
{
    public function viewAny(User $user): Response
    {
    }

    public function view(User $user, Gpoa $gpoa): Response
    {
    }

    public function create(User $user): Response
    {
        return !Gpoa::active()->exists()
            && $user->position_name === 'adviser'
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user): Response
    {
        return Gpoa::active()->exists() 
            && $user->position_name === 'adviser'
            ? Response::allow()
            : Response::deny();
    }

    public function close(User $user): Response
    {
        return Gpoa::active()->exists() 
            && $user->position_name === 'adviser'
            ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user, Gpoa $gpoa): Response
    {
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Gpoa $gpoa): Response
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Gpoa $gpoa): Response
    {
    }


}
