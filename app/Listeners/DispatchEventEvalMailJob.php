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
            $delay = Carbon::parse(
                "{$date->date->format('Y-m-d')} {$date->end_time}",
                $event->timezone);
            PrepareEventEvalMailJob::dispatch($date)->delay($delay);
        }
    }
}
