<?php

namespace App\Listeners;

use App\Events\AccomReportStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendAccomReportStatusChangedMail;
use App\Models\User;

class SendAccomReportStatusNotification
{
    public function __construct()
    {
        //
    }

    public function handle(AccomReportStatusChanged $event): void
    {
        $accomReport = $event->accomReport;
        $status = $accomReport->status;
        $step = $accomReport->current_step;
        $emails = [];
        switch ("{$status}_{$step}") {
        case 'returned_officers':
        case 'approved_adviser':
            foreach (User::accomReportEditor->get() as $officer) {
                $email = $officer->email_verified_at ? $officer->email : null;
                if ($email) {
                    $emails[] = $email;
                }
            }
            break;
        case 'pending_president':
            $email = User::president->first()->email_verified_at
                ? User::president->first()->email : null;
            if ($email) {
                $emails[] = $email;
            }
            break;
        case 'approved_adviser':
            $email = User::adviser->first()->email_verified_at
                ? User::adviser->first()->email : null;
            if ($email) {
                $emails[] = $email;
            }
            break;
        }
        foreach ($emails as $email) {
            SendAccomReportStatusChangedMail::dispatch($email, $accomReport);
        }
    }
}
