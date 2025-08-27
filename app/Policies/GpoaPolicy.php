<?php

namespace App\Policies;

use App\Models\Gpoa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GpoaPolicy
{
    public function viewAny(User $user): Response
    {
        return $user->hasPerm('general-plan-of-activities.view')
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, Gpoa $gpoa): Response
    {
        return $user->hasPerm('general-plan-of-activities.view')
            ? Response::allow()
            : Response::deny();
    }

    public function create(User $user): Response
    {
        $canView = $user->hasPerm('general-plan-of-activities.view');
        $canEdit = $user->hasPerm('general-plan-of-activities.edit');
        if (!($canView && $canEdit)) {
            return Response::deny();
        }
        return !Gpoa::active()->exists()
            && $user->position_name === 'adviser'
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user): Response
    {
        $canView = $user->hasPerm('general-plan-of-activities.view');
        $canEdit = $user->hasPerm('general-plan-of-activities.edit');
        if (!($canView && $canEdit)) {
            return Response::deny();
        }
        return Gpoa::active()->exists() 
            && $user->position_name === 'adviser'
            ? Response::allow()
            : Response::deny();
    }

    public function close(User $user): Response
    {
        $canView = $user->hasPerm('general-plan-of-activities.view');
        $canEdit = $user->hasPerm('general-plan-of-activities.edit');
        if (!($canView && $canEdit)) {
            return Response::deny();
        }
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
