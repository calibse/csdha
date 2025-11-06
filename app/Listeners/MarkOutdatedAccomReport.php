<?php

namespace App\Listeners;

use App\Events\EventUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MarkOutdatedAccomReport
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EventUpdated $event): void
    {
        $accomReport = $event->event->accom_report;
        $accomReport->file_updated = false;
        $accomReport->save();
    }
}
