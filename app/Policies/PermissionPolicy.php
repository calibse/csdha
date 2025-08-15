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
        return ($centralBody)
            ? Response::deny()
            : Response::allow();
    }
}
