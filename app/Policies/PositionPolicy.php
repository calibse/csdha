<?php

namespace App\Policies;

use App\Models\Position;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PositionPolicy
{
    public function viewAny(User $user): Response
    {
        return $user->hasPerm('central-body.view')
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, Position $position): Response
    {
        return $user->hasPerm('central-body.view')
            ? Response::allow()
            : Response::deny();
    }

    public function create(User $user): Response
    {
        return $user->hasPerm('central-body.edit')
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, Position $position): Response
    {
        if (strtolower($position->name) === 'adviser'
                && $user->position_name !== 'adviser')
            return Response::deny();
        return $user->hasPerm('central-body.edit')
            ? Response::allow()
            : Response::deny();
    }

    public function rename(User $user, Position $position): Response
    {
        $adviser = strtolower($position->name) === 'adviser';
        $president = strtolower($position->name) === 'president';
        return ($adviser || $president)
            ? Response::deny()
            : Response::allow();
    }

    public function changePerm(User $user, Position $position,
            Permission $permission): Response
    {
        return Response::allow();
        $positionName = strtolower($position->name);
        switch ($positionName) {
        case 'adviser':
            $edit = $permission->resourceActionType->name === 'edit';
            $centralBody = $permission->resourceType->name === 'central-body';
            if ($edit || $centralBody)  {
                return Response::deny();
            }
            break;
        default:
            $centralBody = $permission->resourceType->name === 'central-body';
            if ($centralBody)  {
                return Response::deny();
            }
        }
    }

    public function removeOfficer(User $user, Position $position): Response
    {
        $adviser = strtolower($position->name) === 'adviser';
        $president = strtolower($position->name) === 'president';
        return ($adviser || $president)
            ? Response::deny()
            : Response::allow();
    }

    public function delete(User $user, Position $position): Response
    {
        $adviser = strtolower($position->name) === 'adviser';
        $president = strtolower($position->name) === 'president';
        return ($adviser || $president)
            ? Response::deny()
            : Response::allow();
    }

    public function restore(User $user, Position $position): Response
    {
        Response::deny();
    }

    public function forceDelete(User $user, Position $position): Response
    {
        Response::deny();
    }
}
