<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AccomReportStatusChanged;
use App\Services\Stream;
use App\Models\AccomReport;
use App\Models\Gpoa;

class EmitPendingAccomReportCountStream
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
    public function handle(AccomReportStatusChanged $event): void
    {
        $gpoa = Gpoa::active()->first();
        if (!$gpoa) return;
        $count = AccomReport::active()->where('status', 'pending')->count();
        $cache = 'home_stream';
        $event = 'pendingAccomReportCountChanged';
        $data = [
            'count' => $count
        ];
        Stream::store($cache, $event, $data);

    }
}
