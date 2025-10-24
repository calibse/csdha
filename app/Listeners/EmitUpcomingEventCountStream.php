<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\EventDatesChanged;
use App\Models\Event;
use App\Models\Gpoa;
use App\Services\Stream;

class EmitUpcomingEventCountStream
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
    public function handle(EventDatesChanged $event): void
    {
        $gpoa = Gpoa::active()->first();
        if (!$gpoa) return;
        $count = Event::active()->upcoming()->count();
        $cache = 'home_stream';
        $event = 'upcomingEventCountChanged';
        $data = [
            'count' => $count
        ];
        Stream::store($cache, $event, $data);
    }
}
