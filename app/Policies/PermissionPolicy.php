<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    public function addPerm(User $user, Permission $permission): Response
    {
        $centralBody = $permission->resourceType->name === 'central-body';
        $edit = $permission->resourceActionType->name === 'edit';
        return ($centralBody && $edit)
            ? Response::deny()
            : Response::allow();
    }
}
