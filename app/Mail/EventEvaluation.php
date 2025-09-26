<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\EventStudent;

class EventEvaluation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
            public EventStudent $attendee,
            public string $eventName,
            public string $url
        )
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "We Value Your Feedback – {$this->eventName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.event-evaluation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
