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
        $activity = $event->activity;
        $status = $activity->status;
        $step = $activity->current_step;
        $emails = [];
        switch ("{$status}_{$step}") {
        case 'rejected_adviser':
        case 'approved_adviser':
            foreach ($activity->eventHeads()->notOfPosition('president')
                    ->verified()->get() as $officer) {
                $emails[] = $officer->email;
            }
            $emails[] = User::president()->verified()->first()?->email;
            break;
        case 'returned_president':
            $emails[] = User::president()->verified()->first()?->email;
            if (!$activity->eventHeads()->ofPosition('president')->exists()) {
                break;
            }
            foreach ($activity->eventHeads()->notOfPosition('president')
                    ->verified()->get() as $officer) {
                $emails[] = $officer->email;
            }
            break;
        case 'returned_officers':
        case 'rejected_president':
            foreach ($activity->eventHeads()->notOfPosition('president')
                    ->verified()->get() as $officer) {
                $emails[] = $officer->email;
            }
            break;
        case 'pending_president':
            $emails[] = User::president()->verified()->first()?->email;
            break;
        case 'pending_adviser':
            $emails[] = User::adviser()->verified()->first()?->email;
            break;
        }
        foreach ($emails as $email) {
            if ($email) {
                SendGpoaActivityStatusChangedMail::dispatch($email, $activity);
            }
        }
    }
}
