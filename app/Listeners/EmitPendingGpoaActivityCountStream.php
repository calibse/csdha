<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\GpoaActivityStatusChanged;
use App\Services\Stream;
use App\Models\Gpoa;

class EmitPendingGpoaActivityCountStream
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
    public function handle(GpoaActivityStatusChanged $event): void
    {
        $gpoa = Gpoa::active()->first();
        if (!$gpoa) return;
	$count = $gpoa->activities()->where('status', 'pending')->count();
        $cache = 'home_stream';
        $event = 'pendingActivityCountChanged';
        $data = [
            'count' => $count
        ];
        Stream::store($cache, $event, $data);

    }
}
