<?php

namespace App\Policies;

use App\Models\GpoaActivity;
use App\Models\Gpoa;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\DB;

class GpoaActivityPolicy
{
    public function viewAny(User $user): Response
    {
        return $user->position_name !== null
            ? Response::allow() : Response::deny();
    }

    public function view(User $user, GpoaActivity $activity): Response
    {
        $canView = $user->hasPerm('general-plan-of-activities.view');
        if (!$canView) {
            return Response::deny();
        }
        $currentStep = $activity->current_step;
        $status = $activity->status;
        $position = $user->position_name;
        if (!in_array($position, ['president', 'adviser', null])) {
            $position = 'officers';
        }
        if ($position === 'officers' || $status === 'approved'
                || $activity->eventHeads()->whereKey($user->id)->exists()) {
            return Response::allow();
        }
        switch ("{$status}_{$currentStep}_{$position}") {
        case 'pending_adviser_adviser':
        case 'pending_adviser_president':
        case 'pending_president_president':
        case 'returned_president_president':
        case 'rejected_adviser_president':
            return Response::allow();
            break;
        }
        return Response::deny();
    }

    public function create(User $user): Response
    {
        if (!self::canEdit($user)) {
            return Response::deny();
        }
        return Gpoa::active()->first()
            && !in_array($user->position_name, ['adviser', null])
            ? Response::allow() : Response::deny();
    }

    public function update(User $user, GpoaActivity $activity): Response
    {
        if (!self::canEdit($user)) {
            return Response::deny();
        }
        $status = $activity->status;
        $currentStep = $activity->current_step;
        $position = $user->position_name;
        if (!in_array($position, ['president', 'adviser', null]))
            $position = 'officers';
        $eventHead = $activity->eventHeads()->whereKey($user->id)->exists();
        $eventHeadIsPresident = $activity->eventHeads()
            ->ofPosition(['president'])->exists();
        $presidentStep = $activity->current_step === 'president';
        $goodStatus = in_array($activity->status, ['returned', 'draft']);
        switch ($position) {
        case 'officers':
            $goodStep = $activity->current_step === 'officers';
            if ($eventHead && $goodStatus && ($goodStep || ($presidentStep &&
                $eventHeadIsPresident))) {
                return Response::allow();
            }
            break;
        case 'president':
            $goodStep = in_array($activity->current_step, ['president',
                'officers']);
            if ($eventHead && $goodStatus && $goodStep) {
                return Response::allow();
            }
            break;
        }
        return Response::deny();
    }

    public function updateEventHeads(User $user, GpoaActivity $activity): Response
    {
        if (!self::canEdit($user)) {
            return Response::deny();
        }
        return $activity->eventHeadsOnly()?->whereKey($user->id)->exists()
            ? Response::allow() : Response::deny();
    }

    public function delete(User $user, GpoaActivity $activity): Response
    {
        return $this->update($user, $activity);
    }

    public function restore(User $user, GpoaActivity $activity): Response
    {
        Response::deny();
    }

    public function forceDelete(User $user, GpoaActivity $activity): Response
    {
        Response::deny();
    }

    public function submit(User $user, GpoaActivity $activity): Response
    {
        if (!self::canEdit($user)) {
            return Response::deny();
        }
        if (!self::canChangeStatus($user, $activity)) {
            return Response::deny();
        }
        $status = $activity->status;
        $currentStep = $activity->current_step;
        $position = $user->position_name;
        if (!in_array($position, ['president', 'adviser', null]))
            $position = 'officers';
        $eventHead = $activity->eventHeads()->whereKey($user->id)->exists();
        $eventHeadIsPresident = $activity->eventHeads()
            ->ofPosition(['president'])->exists();
        $goodStatus = in_array("{$status}_{$currentStep}_{$position}", [
                'draft_officers_officers',
                'draft_officers_president',
                'returned_officers_officers',
                'returned_officers_president',
                'draft_president_president',
                'pending_president_president',
                'returned_president_president',
        ]);
        switch ($position) {
        case 'officers':
            return ($eventHead && !$eventHeadIsPresident && $goodStatus)
                ? Response::allow() : Response::deny();
        case 'president':
            return ($goodStatus)
                ? Response::allow() : Response::deny();
        }
        return Response::deny();
    }

    public function return(User $user, GpoaActivity $activity): Response
    {
        if (!self::canEdit($user)) {
            return Response::deny();
        }
        if (!self::canChangeStatus($user, $activity)) {
            return Response::deny();
        }
        $status = $activity->status;
        $currentStep = $activity->current_step;
        $position = $user->position_name;
        if (!in_array($position, ['president', 'adviser', null]))
            $position = 'officers';
        $eventHead = $activity->eventHeads()->whereKey($user->id)->exists();
        $eventHeadIsPresident = $activity->eventHeads()
            ->ofPosition(['president'])->exists();
        $goodStatus = in_array("{$status}_{$currentStep}_{$position}", [
            'pending_adviser_adviser',
            'pending_president_president',
            'returned_president_president',
        ]);
        switch ($position) {
        case 'president':
            return (!$eventHead && $goodStatus)
                ? Response::allow() : Response::deny();
        case 'adviser':
            return ($goodStatus)
                ? Response::allow() : Response::deny();
        }
        return Response::deny();
    }

    public function reject(User $user, GpoaActivity $activity): Response
    {
        return $this->return($user, $activity);
    }

    public function approve(User $user, GpoaActivity $activity): Response
    {
        if (!self::canEdit($user)) {
            return Response::deny();
        }
        if (!self::canChangeStatus($user, $activity)) {
            return Response::deny();
        }
        $adviser = $user->position_name === 'adviser';
        $currentStep = $activity->current_step === 'adviser';
        $pending = $activity->status === 'pending';
        return ($adviser && $currentStep && $pending)
            ? Response::allow() : Response::deny();
    }

    private static function canEdit(User $user, ?GpoaActivity
            $activity = null): bool
    {
        $canView = $user->hasPerm('general-plan-of-activities.view');
        $canEdit = $user->hasPerm('general-plan-of-activities.edit');
        $gpoaActive = $activity?->gpoa->active;
        return ($canView && $canEdit && ($gpoaActive ?? true));
    }

    private static function canChangeStatus(User $user, GpoaActivity
        $activity): bool
    {
        $status = $activity->status;
        $gpoaActive = $activity->gpoa->active;
        $activityApproved = $status === 'approved';
        return ($gpoaActive && !$activityApproved);
    }
}
