<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\AccomReport;
use App\Mail\AccomReportStatusChanged;
use Illuminate\Support\Facades\Mail;

class SendAccomReportStatusChangedMail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $email, public AccomReport
        $accomReport)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new AccomReportStatusChanged($this
            ->accomReport));
    }
}
