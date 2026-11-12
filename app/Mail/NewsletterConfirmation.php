<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $confirmUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->email,
            subject: 'Please confirm your JMOR newsletter subscription',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.newsletter-confirmation',
            with: [
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'email' => $this->email,
                'confirmUrl' => $this->confirmUrl,
            ],
        );
    }
}
