<?php

namespace App\Listeners;

use App\Events\EventDateDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CancelEventEvalMailJob
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
    public function handle(EventDateDeleted $event): void
    {
        //
    }
}
