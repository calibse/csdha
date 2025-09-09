<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignupInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $url)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CSDHA Signup Invitation',
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.signupInvitation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
