<?php

namespace App\Listeners;

use App\Events\EventUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\PrepareEventEvalMailJob;
use Illuminate\Support\Carbon;

class DispatchEventEvalMailJob
{
    public function __construct()
    {
        //
    }

    public function handle(EventUpdated $event): void
    {
        $event = $event->event;
        if (!$event->accept_evaluation) return;
        foreach ($event->dates as $date) {
            $delay = $date->end_date->setTimezone($event->timezone);
            // $delay = 0;
            PrepareEventEvalMailJob::dispatch($date->id)->delay($delay);
        }
    }
}
