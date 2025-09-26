<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\EventEvaluation as EventEvaluationMail;
use App\Models\Event;
use App\Models\EventStudent;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Mail;

class SendEventEvalMailJob implements ShouldQueue
{
    use Batchable, Queueable;

    public function __construct(
            public EventStudent $attendee,
            public Event $event,
            public string $url
        )
    {
        //
    }

    public function handle(): void
    {
        if (!$event->accept_evaluation) $this->batch()->cancel();
        Mail::to($attendee->email)->send(new EventEvaluationMail(
            $attendee, $event->gpoaActivity->name, $url));
        $attendee->eval_mail_sent = true;
        $attendee->save();
    }
}
