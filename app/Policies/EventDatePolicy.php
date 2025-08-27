<?php

namespace App\Policies;

use App\Models\EventDate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventDatePolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, EventDate $eventDate): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, EventDate $eventDate): Response
    {
        $hasAttendees = $eventDate->attendees()->exists();
        return ($hasAttendees)
            ? Response::deny()
            : Response::allow();
    }

    public function storeAttendance(User $user, EventDate $eventDate): Response
    {
        $canView = $user->hasPerm('attendance.view');
        $canEdit = $user->hasPerm('attendance.edit');
        if (!($canView && $canEdit)) {
            return Response::deny();
        }
        $approved = $event->accomReport->status === 'approved';
        $pending = $event->accomReport->status === 'pending';
        return ($approved || $pending)
            ? Response::deny()
            : Response::allow();
    }

    public function delete(User $user, EventDate $eventDate): bool
    {
        return false;
    }

    public function restore(User $user, EventDate $eventDate): bool
    {
        return false;
    }

    public function forceDelete(User $user, EventDate $eventDate): bool
    {
        return false;
    }
}
