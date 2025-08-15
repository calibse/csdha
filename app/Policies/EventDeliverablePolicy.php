<?php

namespace App\Policies;

use App\Models\EventDeliverable;
use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\Response;

class EventDeliverablePolicy
{

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, EventDeliverable $eventDeliverable): bool
    {
        return false;
    }

    public function create(User $user, Event $event): Response
    {
        return (
            $event->creator-is($user)
            || $event->editors->contains($user)
        ) ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, EventDeliverable $eventDeliverable): Response
    {
        return $eventDeliverable->assignees->contains($user)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventDeliverable $eventDeliverable): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EventDeliverable $eventDeliverable): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EventDeliverable $eventDeliverable): bool
    {
        return false;
    }
}
