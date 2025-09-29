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
            ? Response::allow() : Response::deny();
    }

    public function view(User $user, Event $event): Response
    {
        $active = $event->gpoa()->active()->exists();
        if (!$active) return Response::deny();
        return ($user->hasPerm('events.view'))
            ? Response::allow() : Response::deny();
    }

    public function create(User $user): Response
    {
        if (!self::canEdit($user)) {
            return Response::deny();
        }
        return Response::allow();
    }

    public function update(User $user, Event $event): Response
    {
        $active = $event->gpoa()->active()->exists();
        if (!$active) return Response::deny();
        if (!self::canEdit($user, $event)) {
            return Response::deny();
        }
        $approved = $event->accomReport?->status === 'approved';
        $pending = $event->accomReport?->status === 'pending';
        $eventHead = $event->gpoaActivity->eventHeads()->whereKey($user->id)
            ->exists();
        return (!($approved || $pending) && $eventHead)
            ? Response::allow() : Response::deny();
    }

    public function delete(User $user, Event $event): Response
    {
        if (!self::canEdit($user, $event)) {
            return Response::deny();
        }
        return ($event->creator->is($user) ||
            $event->editors->contains($user))
            ? Response::allow() : Response::deny();
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
            ? Response::allow() : Response::deny();
    }

    public function viewAccomReport(User $user, Event $event): Response
    {
        $active = $event->gpoa()->active()->exists();
        if (!$active) return Response::deny();
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
        $active = $event->gpoa()->active()->exists();
        if (!$active) return Response::deny();
        if (!self::canEdit($user, $event)) {
            return Response::deny();
        }
        if (!self::canChangeStatus($user, $event)) {
            return Response::deny();
        }
        $canView = $user->hasPerm('accomplishment-reports.view');
        $canEdit = $user->hasPerm('accomplishment-reports.edit');
        if (!$canEdit || !$canView) {
            return Response::deny();
        }
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
        $active = $event->gpoa()->active()->exists();
        if (!($active && self::canChangeStatus($user, $event))) {
            return Response::deny();
        }
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
        $active = $event->gpoa()->active()->exists();
        if (!($active && self::canChangeStatus($user, $event))) {
            return Response::deny();
        }
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

    public function genAccomReport(User $user, Event $event): Response
    {
        $active = $event->gpoa()->active()->exists();
        if (!$active) return Response::deny();
    }

    public function register(?User $user, Event $event): Response
    {
        $active = $event->gpoa()->active()->exists();
        if (!$active) return Response::deny();
        $openRegis = $event->automatic_attendance;
        return ($openRegis) ? Response::allow() : Response::deny();
    }

    public function evaluate(?User $user, Event $event): Response
    {
        $active = $event->gpoa()->active()->exists();
        if (!$active) return Response::deny();
        $openEval = $event->accept_evaluation;
        return ($openEval) ? Response::allow() : Response::deny();
    }

    public function recordAttendance(User $user, Event $event): Response
    {
        $active = $event->gpoa()->active()->exists();
        if (!$active) return Response::deny();
        $openEval = $event->participant_type;
        return ($openEval) ? Response::allow() : Response::deny();
    }

    public function addAttendee(User $user, Event $event): Response
    {
        $active = $event->gpoa()->active()->exists();
        if (!$active) return Response::deny();
        $recordsAttendance = $event->participant_type !== null;
        $manualAttendance = $event->automatic_attendance === 0;
        return ($recordsAttendance && $manualAttendance)
            ? Response::allow() : Response::deny();
    }

    public function viewAttendance(User $user): Response
    {
        $canView = $user->hasPerm('attendance.view');
        $canEdit = $user->hasPerm('attendance.edit');
        if (!($canView && $canEdit)) {
            return Response::deny();
        }
        return Response::allow();
    }

    private static function canEdit(User $user, ?Event $event = null): bool
    {
        $canView = $user->hasPerm('events.view');
        $canEdit = $user->hasPerm('events.edit');
        $approved = $event?->accomReport?->status === 'approved';
        return ($canView && $canEdit && !($approved ?? false));
    }

    private static function canChangeStatus(User $user, ?Event
        $event = null): bool
    {
        $approved = $event?->accomReport?->status === 'approved';
        return (!($approved ?? false));
    }
}
