<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\GpoaActivity;
use App\Services\Format;

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
        $text = $this->messageText();
        return new Content(
            text: 'emails.gpoa-activity-status-changed',
            with: [
                'name' => $this->activity->name,
                'status' => $this->activity->status,
                'step' => $this->activity->current_step,
                'url' => route('gpoa.activities.show', [
                    'activity' => $this->activity->public_id
                ]),
                'action' => $text['action'],
                'position' => $text['position'],
                'date' => $text['date'],
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function messageText(): array
    {
        switch ($this->activity->current_step) {
        case 'officers':
            switch ($this->activity->status) {
            case 'returned':
                $action = 'returned';
                $position = 'President';
                $date = $this->activity->president_returned_at;
                break;
            }
            break;
        case 'president':
            switch ($this->activity->status) {
            case 'pending':
                $action = 'submitted';
                $position = 'officers';
                $date = $this->activity->officers_submitted_at;
                break;
            case 'returned':
                $action = 'returned';
                $position = 'Adviser';
                $date = $this->activity->adviser_returned_at;
                break;
            case 'rejected':
                $action = 'rejected';
                $position = 'President';
                $date = $this->activity->president_rejected_at;
                break;
            }
            break;
        case 'adviser':
            switch ($this->activity->status) {
            case 'pending':
                $action = 'submitted';
                $position = 'President';
                $date = $this->activity->president_submitted_at;
                break;
            case 'rejected':
                $action = 'rejected';
                $position = 'Adviser';
                $date = $this->activity->adviser_rejected_at;
                break;
            case 'approved':
                $action = 'approved';
                $position = 'Adviser';
                $date = $this->activity->adviser_approved_at;
                break;
            }
            break;
        }
        $date = Format::toPh($date)->format(config('app.date_format')) . 
            ' Asia/Manila';
        return [
            'action' => $action,
            'position' => $position,
            'date' => $date
        ];
    }
}
