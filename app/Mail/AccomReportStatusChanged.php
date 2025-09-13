<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\AccomReport;

class AccomReportStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AccomReport $accomReport)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'An accomplishment report status changed',
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.accom-report-status-changed',
            with: [
                'eventName' => $this->accomReport->event->gpoaActivity->name,
                'status' => $this->accomReport->status,
                'step' => $this->accomReport->current_step,
                'url' => route('accom-reports.show', [
                    'event' => $this->accomReport->event->public_id
                ])
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
