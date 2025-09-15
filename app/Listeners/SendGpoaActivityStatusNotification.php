<?php

namespace App\Listeners;

use App\Events\GpoaActivityStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendGpoaActivityStatusChangedMail;
use App\Models\User;

class SendGpoaActivityStatusNotification
{
    public function __construct()
    {
        //
    }

    public function handle(GpoaActivityStatusChanged $event): void
    {
        return;
        $activity = $event->activity;
        $status = $activity->status;
        $step = $activity->current_step;
        $emails = [];
        switch ("{$status}_{$step}") {
        case 'returned_officers':
        case 'rejected_president':
        case 'rejected_adviser':
        case 'approved_adviser':
            foreach (activity->eventHeads() as $officer) {
                $email = $officer->email;
                if ($email) {
                    $emails[] = $email;
                }
            }
        case 'pending_president':
        case 'returned_president':
        case 'rejected_adviser':
        case 'approved_adviser':
            $email = User::president->first()->email;
            if ($email) {
                $emails[] = $email;
            }
        case 'pending_adviser':
            $email = User::adviser->first()->email;
            if ($email) {
                $emails[] = $email;
            }
        }
        foreach ($emails as $email) {
            SendGpoaActivityStatusChangedMail::dispatch($email, $activity);
        }
    }
}
