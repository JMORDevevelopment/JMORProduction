<?php

namespace App\Mail;

use App\Models\TalkShow;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TalkShowStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TalkShow $application,
        public int $newStatus,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your JMOR Talk Show application status: '.TalkShow::statusLabel($this->newStatus),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.talk-show-status-changed',
            with: [
                'application' => $this->application,
                'statusLabel' => TalkShow::statusLabel($this->newStatus),
            ],
        );
    }
}
