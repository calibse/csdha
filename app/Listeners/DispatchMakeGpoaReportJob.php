<?php

namespace App\Listeners;

use App\Events\GpoaUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\MakeGpoaReport;

class DispatchMakeGpoaReportJob
{
    public function __construct()
    {
        //
    }

    public function handle(GpoaUpdated $event): void
    {
	$gpoa = $event->gpoa;
        $gpoa->report_file_updated = false;
        $gpoa->save();
        MakeGpoaReport::dispatch($gpoa, auth()->user())->onQueue('pdf');
    }
}
