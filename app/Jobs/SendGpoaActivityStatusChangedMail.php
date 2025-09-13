<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\GpoaActivity;
use App\Mail\GpoaActivityStatusChanged;

class SendGpoaActivityStatusChangedMail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $email, public GpoaActivity
        $activity)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new GpoaActivityStatusChanged($this
            ->activity));
    }
}
