<?php

namespace App\Mail;

use App\Models\TalkShow;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent from the admin "Send Revision" action with a chosen template body.
 * Original: CI Talk_show_guests::send_revision() (broken — no recipient);
 * this delivers the same template content to the applicant's email.
 */
class TalkShowRevision extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TalkShow $applicant,
        public string $body,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Talk Show',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.talk-show-revision',
            with: [
                'name' => trim(($this->applicant->name ?? '').' '.($this->applicant->last_name ?? '')),
                'body' => $this->body,
            ],
        );
    }

    /**
     * @return array<int, mixed>
     */
    public function attachments(): array
    {
        return [];
    }
}
