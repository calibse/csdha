<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{   
    public function viewAny(User $user): Response
    {
        return $user->hasPerm('events.view')
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, Event $event): Response
    {
        return (
            $user->hasPerm('events.view')
        ) ? Response::allow()
            : Response::deny();
    }

    public function create(User $user): Response
    {
        return $user->hasPerm('events.edit')
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, Event $event): Response
    {
        return (
            true
        ) ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user, Event $event): Response
    {
        return (
            $event->creator->is($user)
            || $event->editors->contains($user)
        ) ? Response::allow()
            : Response::deny();
    }

    public function restore(User $user, Event $event): bool
    {
        return false;
    }

    public function forceDelete(User $user, Event $event): bool
    {
        return false;
    }

    public function viewAnyAccomReport(User $user): Response
    {
        $withPosition = $user->position_name;
        $hasPerm = $user->hasPerm('accomplishment-reports.view');
        return ($hasPerm && $withPosition)
            ? Response::allow()
            : Response::deny();
    }

    public function viewAccomReport(User $user, Event $event): Response
    {
        $position = $user->position_name;
        if (!in_array($position, ['adviser', 'president', null])) {
            $position = 'officers';
        }
        switch ($position) {
        case 'officers':
            $head = $event->gpoaActivity->eventHeads()->whereKey($user->id)->exists();
            $hasPerm = $user->hasPerm('accomplishment-reports.view');
            if ($head || $hasPerm) {
                return Response::allow();
            }
            break;
        case 'president':
            $head = $event->gpoaActivity->eventHeads()->whereKey($user->id)->exists();
            $pending = $event->accomReport?->status === 'pending';
            $currentStep = $event->accomReport?->current_step === 'president';
            $approved = $event->accomReport?->status === 'approved';
            if ($head || (($pending && $currentStep) || $approved)) {
                return Response::allow();
            }
            break;
        case 'adviser':
            $approved = $event->accomReport?->status === 'approved';
            if ($approved) {
                return Response::allow();
            }
            break;
        }
        return Response::deny();
    }

    public function submitAccomReport(User $user, Event $event): Response
    {
        $position = $user->position_name;
        if (!in_array($position, ['adviser', 'president', null])) {
            $position = 'officers';
        }
        switch ($position) {
        case 'officers':
            $hasPerm = $user->hasPerm('accomplishment-reports.view');
            $notSubmitted = $event->accomReport ? false : true;
            $returned = $event->accomReport?->status === 'returned';
            $currentStep = $event->accomReport?->current_step === 'officers';
            if ($hasPerm && ($notSubmitted || ($returned && $currentStep))) {
                return Response::allow();
            }
            break;
        }
        return Response::deny();
    }

    public function returnAccomReport(User $user, Event $event): Response
    {
        $position = $user->position_name;
        if (!in_array($position, ['adviser', 'president', null])) {
            $position = 'officers';
        }
        switch ($position) {
        case 'president':
            $pending = $event->accomReport?->status === 'pending';
            $currentStep = $event->accomReport?->current_step === 'president';
            if ($pending && $currentStep) {
                return Response::allow();
            }
            break;
        }
        return Response::deny();
    }

    public function approveAccomReport(User $user, Event $event): Response
    {
        $position = $user->position_name;
        if (!in_array($position, ['adviser', 'president', null])) {
            $position = 'officers';
        }
        switch ($position) {
        case 'president':
            $pending = $event->accomReport?->status === 'pending';
            $currentStep = $event->accomReport?->current_step === 'president';
            if ($pending && $currentStep) {
                return Response::allow();
            }
            break;
        }
        return Response::deny();
    }

    public function register(?User $user, Event $event): Response
    {
        $openRegis = $event->automatic_attendance;
        return ($openRegis)
            ? Response::allow()
            : Response::deny();
    }

    public function evaluate(?User $user, Event $event): Response
    {
        $openEval = $event->accept_evaluation;
        return ($openEval)
            ? Response::allow()
            : Response::deny();
    }

    public function recordAttendance(User $user, Event $event): Response
    {
        $openEval = $event->participant_type;
        return ($openEval)
            ? Response::allow()
            : Response::deny();
    }

}
