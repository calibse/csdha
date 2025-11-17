<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, User $model): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, User $model): bool
    {
        return false;
    }

    public function updatePassword(User $user): bool
    {
        $hasGoogle = $user->google;
        $hasEmail = $user->email_verified_at;
        return ($hasGoogle || $hasEmail);
    }

    public function updateEmail(User $user): bool
    {
        $hasGoogle = $user->google;
        $hasPassword = !is_null($user->password);
        return ($hasGoogle || $hasPassword);
    }

    public function delete(User $user, User $model): Response
    {
        $userIsAdmin = strtolower($user->role?->name) === 'admin';
        if (!$userIsAdmin) Response::deny();
        $admin = strtolower($model->role?->name) === 'admin';
        $president = strtolower($model->position?->name) === 'president';
        $adviser = strtolower($model->position?->name) === 'adviser';
        return ($admin || $president || $adviser)
            ? Response::deny()
            : Response::allow();
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
