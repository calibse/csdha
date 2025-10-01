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
        case 'approved_adviser':
            foreach (User::accomReportEditor()->verified()->get() as $officer) {
                $emails[] = $officer->email;
            }
            $emails[] = User::adviser()->verified()->first()?->email;
            break;
        case 'returned_officers':
            foreach (User::accomReportEditor()->verified()->get() as $officer) {
                $emails[] = $officer->email;
            }
            break;
        case 'pending_president':
            $emails[] = User::president()->verified()->first()?->email;
            break;
        }
        foreach ($emails as $email) {
            if ($email) {
                SendAccomReportStatusChangedMail::dispatch($email,
                    $accomReport);
            }
        }
    }
}
