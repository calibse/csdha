<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\EventEvaluation as EventEvaluationMail;
use App\Models\EventDate;
use App\Models\EventStudent;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendEventEvalMailJob implements ShouldQueue, ShouldBeUnique
{
    use Batchable, Queueable;

    public function __construct(
            public int $attendeeId,
            public int $eventDateId,
            public string $url
        )
    {
        //
    }

    public function handle(): void
    {
        $attendee = EventStudent::find($attendeeId);
        $eventDate = EventDate::find($eventDateId);
        if (!$eventDate) $this->batch()->cancel();
        if (!$attendee) return;
        $event = $this->eventDate->event;
        if (!$event->accept_evaluation) $this->batch()->cancel();
        $attendee = $this->eventDate->attendees()->find($attendee->id);
        if ($attendee->eventAttendee->eval_mail_sent) return;
        $url = $this->url;
        Mail::to($attendee->email)->send(new EventEvaluationMail(
            $attendee, $event->gpoaActivity->name, $url));
        $attendee->eventAttendee->eval_mail_sent = true;
        $attendee->eventAttendee->save();
    }

    public function uniqueId(): string
    {
        return "{$this->eventDateId}_{$this->attendeeId}";
    }
}
