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
        case 'returned_officers':
        case 'rejected_president':
        case 'rejected_adviser':
        case 'approved_adviser':
            foreach ($activity->eventHeads()->notOfPosition('president')->get()
                    as $officer) {
                $email = $officer->email_verified_at ? $officer->email : null;
                if ($email) {
                    $emails[] = $email;
                }
            }
            break;
        case 'pending_president':
        case 'returned_president':
        case 'rejected_adviser':
        case 'approved_adviser':
            $email = User::president()->first()->email_verified_at
                ? User::president()->first()->email : null;
            if ($email) {
                $emails[] = $email;
            }
            break;
        case 'pending_adviser':
            $email = User::adviser()->first()->email_verified_at
                ? User::adviser()->first()->email : null;
            if ($email) {
                $emails[] = $email;
            }
            break;
        }
        foreach ($emails as $email) {
            SendGpoaActivityStatusChangedMail::dispatch($email, $activity);
        }
    }
}
