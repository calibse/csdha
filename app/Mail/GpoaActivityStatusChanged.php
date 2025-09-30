<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\GpoaActivity;

class GpoaActivityStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public GpoaActivity $activity)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A GPOA Activity Status Changed',
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.gpoa-activity-status-changed',
            with: [
                'name' => $this->activity->name,
                'status' => $this->activity->status,
                'step' => $this->activity->current_step,
                'url' => route('gpoa.activities.show', [
                    'activity' => $this->activity->public_id
                ])
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
