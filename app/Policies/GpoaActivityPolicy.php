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
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, GpoaActivity $activity): Response
    {
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
        return Gpoa::active()->first()
            && !in_array($user->position_name, ['adviser', null])
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, GpoaActivity $activity): Response
    {
        return $activity->gpoa->active
            && !in_array($user->position_name, ['adviser', null])
            && $activity->eventHeads()->whereKey($user->id)->exists()
            && in_array($activity->status, ['returned', 'draft'])
            && in_array($activity->current_step, ['officers', 'president'])
            ? Response::allow()
            : Response::deny();
    }
    
    public function updateEventHeads(User $user, GpoaActivity $activity): Response
    {
        return $activity->coheads()?->whereKey($user->id)->exists()
            ? Response::deny()
            : Response::allow();
    }

    public function delete(User $user, GpoaActivity $activity): Response
    {
        return $activity->gpoa->active
            && !in_array($user->position_name, ['adviser', null])
            && $activity->eventHeads()->whereKey($user->id)->exists()
            && in_array($activity->status, ['returned', 'draft'])
            && in_array($activity->current_step, ['officers', 'president'])
            ? Response::allow()
            : Response::deny();
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
        if (!$activity->gpoa->active) Response::deny();
        $status = $activity->status;
        if ($status === 'approved') {
            return Response::deny();
        }
        $currentStep = $activity->current_step;
        $eventHead = $activity->eventHeads()->whereKey($user->id)->exists() 
            ? true : false; 
        $position = $user->position_name;
        if (!in_array($position, ['president', 'adviser', null]))
            $position = 'officers';
        if ($activity->eventHeads()->ofPosition(['president'])->first()
                && $position === 'officers') {
            return Response::deny();
        }
        switch ("{$status}_{$currentStep}_{$position}_{$eventHead}") {
        case 'draft_officers_officers_1':
        case 'draft_officers_president_1':
        case 'returned_officers_officers_1':
        case 'returned_officers_president_1':
        case 'draft_president_president_1':
        case 'draft_president_president_':
        case 'pending_president_president_1':
        case 'pending_president_president_':
        case 'returned_president_president_1':
        case 'returned_president_president_':
            return Response::allow();
            break;
        }
        return Response::deny();
    }

    public function return(User $user, GpoaActivity $activity): Response
    {
        if (!$activity->gpoa->active) Response::deny();
        $eventHeads = $activity->eventHeads;
        if ($activity->eventHeads()->whereRelation('position',
                DB::raw('lower(name)'), 'president')
            && $user->position_name === 'president') {
            return Response::deny();
        }
        $status = $activity->status;
        $position = $user->position_name;
        $currentStep = $activity->current_step;
        switch ("{$status}_{$currentStep}_{$position}") {
        case 'pending_adviser_adviser':
        case 'pending_president_president':
        case 'returned_president_president':
            return Response::allow();
            break;
        }
        return Response::deny();
    }

    public function reject(User $user, GpoaActivity $activity): Response
    {
        if (!$activity->gpoa->active) Response::deny();
        $eventHeads = $activity->eventHeads;
        if ($activity->eventHeads()->whereRelation('position',
                DB::raw('lower(name)'), 'president')
            && $user->position_name === 'president') {
            return Response::deny();
        }
        $status = $activity->status;
        $position = $user->position_name;
        $currentStep = $activity->current_step;
        switch ("{$status}_{$currentStep}_{$position}") {
        case 'pending_adviser_adviser':
        case 'pending_president_president':
        case 'returned_president_president':
            return Response::allow();
            break;
        }
        return Response::deny();
    }

    public function approve(User $user, GpoaActivity $activity): Response
    {
        if (!$activity->gpoa->active) Response::deny();
        return $user->position_name === 'adviser' 
            && $activity->current_step === 'adviser'
            && $activity->status === 'pending'
            ? Response::allow()
            : Response::deny();
    }
}
