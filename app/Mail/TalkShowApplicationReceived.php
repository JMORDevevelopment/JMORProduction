<?php

namespace App\Mail;

use App\Models\TalkShow;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TalkShowApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TalkShow $application,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your JMOR Talk Show Guest Application',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.talk-show-received',
            with: ['application' => $this->application],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $path = $this->application->signed_pdf_path;

        if (blank($path) || ! Storage::disk('local')->exists($path)) {
            return [];
        }

        return [
            Attachment::fromStorageDisk('local', $path)
                ->as('talk-show-application-signed.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
