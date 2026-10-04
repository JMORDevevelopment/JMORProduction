<?php

namespace App\Mail;

use App\Models\TalkShow;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent when an admin accepts a talk show guest applicant.
 * Original: CI Talk_show_guests::accept() rendered views/frontend/email_temp
 * ("Dear User ... Click here to order").
 */
class TalkShowAcceptance extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public TalkShow $applicant) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Talk Show',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.talk-show-acceptance',
            with: [
                'name' => trim(($this->applicant->name ?? '').' '.($this->applicant->last_name ?? '')),
                'orderUrl' => route('talk-show.checkout'),
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
