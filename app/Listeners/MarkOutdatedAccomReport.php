<?php

namespace App\Listeners;

use App\Events\EventUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MarkOutdatedAccomReport
{
    public function __construct()
    {
        //
    }

    public function handle(EventUpdated $event): void
    {
        $accomReport = $event->event->accomReport;
        $accomReport->file_updated = false;
        $accomReport->save();
    }
}
